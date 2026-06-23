<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserCard;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.secret'));
    }

    public function getOrCreateCustomer(User $user): string
    {
        // Return existing Stripe customer if user already has a card
        $existing = $user->cards()->value('stripe_customer_id');
        if ($existing) {
            return $existing;
        }

        $customer = $this->stripe->customers->create([
            'email'    => $user->email,
            'name'     => $user->full_name,
            'phone'    => $user->phone_number,
            'metadata' => ['user_id' => $user->id, 'uuid' => $user->uuid],
        ]);

        return $customer->id;
    }

    public function createSetupIntent(User $user): array
    {
        $customerId = $this->getOrCreateCustomer($user);

        $setupIntent = $this->stripe->setupIntents->create([
            'customer'             => $customerId,
            'payment_method_types' => ['card'],
            'usage'                => 'off_session',
            'metadata'             => ['user_id' => $user->id],
        ]);

        return [
            'client_secret'  => $setupIntent->client_secret,
            'customer_id'    => $customerId,
            'setup_intent_id' => $setupIntent->id,
        ];
    }

    public function createPaymentIntent(
        User $user,
        float $amountInSourceCurrency,
        string $sourceCurrency,
        float $amountInCny,
        string $paymentMethodId,
        array $metadata = []
    ): array {
        $customerId = $this->getOrCreateCustomer($user);

        // Stripe charges in the smallest currency unit
        $amountInCents = $this->toSmallestUnit($amountInSourceCurrency, $sourceCurrency);

        $paymentIntent = $this->stripe->paymentIntents->create([
            'amount'               => $amountInCents,
            'currency'             => strtolower($sourceCurrency),
            'customer'             => $customerId,
            'payment_method'       => $paymentMethodId,
            'confirm'              => true,
            'off_session'          => false,
            'capture_method'       => config('stripe.payment_intent.capture_method'),
            'setup_future_usage'   => config('stripe.payment_intent.setup_future_usage'),
            'description'          => "AfriYuan transfer — {$amountInCny} CNY",
            'metadata'             => array_merge($metadata, [
                'user_id'      => $user->id,
                'source_amount' => $amountInSourceCurrency,
                'source_currency' => $sourceCurrency,
                'receive_amount_cny' => $amountInCny,
                'platform'     => 'afriyuan',
            ]),
            'return_url'           => config('app.url') . '/payment/return',
        ]);

        return [
            'payment_intent_id' => $paymentIntent->id,
            'client_secret'     => $paymentIntent->client_secret,
            'status'            => $paymentIntent->status,
            'requires_action'   => $paymentIntent->status === 'requires_action',
        ];
    }

    public function attachPaymentMethod(User $user, string $paymentMethodId): UserCard
    {
        $customerId = $this->getOrCreateCustomer($user);

        // Attach to Stripe customer
        $this->stripe->paymentMethods->attach($paymentMethodId, [
            'customer' => $customerId,
        ]);

        // Retrieve details to store in DB
        $pm = $this->stripe->paymentMethods->retrieve($paymentMethodId);
        $card = $pm->card;

        // Remove default from other cards
        $user->cards()->update(['is_default' => false]);

        return $user->cards()->create([
            'stripe_payment_method_id' => $paymentMethodId,
            'stripe_customer_id'       => $customerId,
            'card_brand'               => $card->brand,
            'last_four'                => $card->last4,
            'exp_month'                => $card->exp_month,
            'exp_year'                 => $card->exp_year,
            'cardholder_name'          => $pm->billing_details->name,
            'billing_country'          => $pm->billing_details->address->country ?? null,
            'fingerprint'              => $card->fingerprint,
            'funding'                  => $card->funding ?? 'unknown',
            'three_d_secure_usage'     => $card->three_d_secure_usage?->supported ? 'optional' : 'not_supported',
            'is_default'               => true,
        ]);
    }

    public function detachPaymentMethod(UserCard $userCard): void
    {
        $this->stripe->paymentMethods->detach($userCard->stripe_payment_method_id);
        $userCard->update(['is_active' => false]);
    }

    public function refundPayment(string $chargeId, ?int $amountInCents = null): string
    {
        $params = ['charge' => $chargeId];
        if ($amountInCents) {
            $params['amount'] = $amountInCents;
        }
        $refund = $this->stripe->refunds->create($params);
        return $refund->id;
    }

    public function constructWebhookEvent(string $payload, string $sigHeader): \Stripe\Event
    {
        return \Stripe\Webhook::constructEvent(
            $payload,
            $sigHeader,
            config('stripe.webhook_secret')
        );
    }

    private function toSmallestUnit(float $amount, string $currency): int
    {
        // Zero-decimal currencies
        $zeroDecimal = ['XOF', 'XAF', 'GNF', 'JPY', 'KRW'];
        if (in_array(strtoupper($currency), $zeroDecimal)) {
            return (int) round($amount);
        }
        return (int) round($amount * 100);
    }
}
