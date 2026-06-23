<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CreateTransferRequest;
use App\Http\Requests\Api\V1\TransferQuoteRequest;
use App\Http\Resources\Api\V1\TransactionResource;
use App\Models\Transaction;
use App\Services\StripeService;
use App\Services\TransferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function __construct(
        private readonly TransferService $transferService,
        private readonly StripeService   $stripeService,
    ) {}

    public function quote(TransferQuoteRequest $request): JsonResponse
    {
        $quote = $this->transferService->quote($request->validated());

        return response()->json(['data' => $quote]);
    }

    public function store(CreateTransferRequest $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->isKycApproved()) {
            return response()->json(['message' => 'KYC verification required before sending money.'], 403);
        }

        $transaction = $this->transferService->create($user, $request->validated());

        // If payment method is card, create Stripe PaymentIntent
        $stripeData = null;
        if ($request->payment_method === 'card' && $request->payment_method_id) {
            $stripeData = $this->stripeService->createPaymentIntent(
                user: $user,
                amountInSourceCurrency: (float) $request->send_amount,
                sourceCurrency: $request->from_currency,
                amountInCny: (float) $transaction->receive_amount,
                paymentMethodId: $request->payment_method_id,
                metadata: ['transaction_uuid' => $transaction->uuid]
            );

            $transaction->update([
                'stripe_payment_intent_id' => $stripeData['payment_intent_id'],
                'status' => 'payment_pending',
            ]);
        }

        return response()->json([
            'message' => 'Transfer initiated successfully.',
            'data' => [
                'transaction'  => new TransactionResource($transaction->load('beneficiary')),
                'stripe'       => $stripeData,
            ],
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $transactions = Transaction::forUser($request->user()->id)
            ->with('beneficiary')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->direction, fn ($q) => $q->where('direction', $request->direction))
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => TransactionResource::collection($transactions->items()),
            'meta' => [
                'current_page' => $transactions->currentPage(),
                'last_page'    => $transactions->lastPage(),
                'per_page'     => $transactions->perPage(),
                'total'        => $transactions->total(),
            ],
        ]);
    }

    public function show(Request $request, string $uuid): JsonResponse
    {
        $transaction = Transaction::where('uuid', $uuid)
            ->where('user_id', $request->user()->id)
            ->with(['beneficiary.country', 'beneficiary.currency', 'statusLogs'])
            ->firstOrFail();

        return response()->json(['data' => new TransactionResource($transaction)]);
    }

    public function cancel(Request $request, string $uuid): JsonResponse
    {
        $transaction = Transaction::where('uuid', $uuid)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        if (!$transaction->canBeCancelled()) {
            return response()->json(['message' => 'This transaction cannot be cancelled.'], 422);
        }

        $transaction->update([
            'status'       => 'cancelled',
            'cancelled_by' => 'user',
            'cancelled_at' => now(),
        ]);

        $transaction->statusLogs()->create([
            'from_status'     => $transaction->getOriginal('status'),
            'to_status'       => 'cancelled',
            'changed_by_type' => 'user',
            'changed_by_id'   => $request->user()->id,
        ]);

        return response()->json(['message' => 'Transaction cancelled.']);
    }
}
