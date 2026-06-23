<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\StripeWebhook;
use App\Models\Transaction;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StripeWebhookController extends Controller
{
    public function __construct(private readonly StripeService $stripeService) {}

    public function handle(Request $request): Response
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = $this->stripeService->constructWebhookEvent($payload, $sigHeader);
        } catch (\Exception $e) {
            return response('Invalid signature.', 400);
        }

        // Idempotency: skip already processed events
        if (StripeWebhook::where('stripe_event_id', $event->id)->where('processed', true)->exists()) {
            return response('Already processed.', 200);
        }

        $webhook = StripeWebhook::firstOrCreate(
            ['stripe_event_id' => $event->id],
            ['event_type' => $event->type, 'payload' => $event->toArray()]
        );

        try {
            $this->processEvent($event);
            $webhook->markProcessed();
        } catch (\Throwable $e) {
            $webhook->markFailed($e->getMessage());
            return response('Processing failed.', 500);
        }

        return response('OK', 200);
    }

    private function processEvent(\Stripe\Event $event): void
    {
        match ($event->type) {
            'payment_intent.succeeded'     => $this->handlePaymentSucceeded($event->data->object),
            'payment_intent.payment_failed' => $this->handlePaymentFailed($event->data->object),
            'charge.refunded'              => $this->handleChargeRefunded($event->data->object),
            default                        => null,
        };
    }

    private function handlePaymentSucceeded(\Stripe\PaymentIntent $paymentIntent): void
    {
        $transaction = Transaction::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        if (!$transaction) return;

        $transaction->update([
            'stripe_charge_id'       => $paymentIntent->latest_charge,
            'status'                 => 'payment_confirmed',
            'payment_confirmed_at'   => now(),
        ]);

        $transaction->statusLogs()->create([
            'from_status'     => 'payment_pending',
            'to_status'       => 'payment_confirmed',
            'changed_by_type' => 'webhook',
            'notes'           => 'Stripe payment_intent.succeeded',
        ]);
    }

    private function handlePaymentFailed(\Stripe\PaymentIntent $paymentIntent): void
    {
        $transaction = Transaction::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        if (!$transaction) return;

        $transaction->update([
            'status'         => 'failed',
            'failure_reason' => $paymentIntent->last_payment_error?->message,
        ]);
    }

    private function handleChargeRefunded(\Stripe\Charge $charge): void
    {
        $transaction = Transaction::where('stripe_charge_id', $charge->id)->first();
        if (!$transaction) return;

        $transaction->update(['status' => 'refunded']);
    }
}
