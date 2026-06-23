<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function __construct(private readonly StripeService $stripeService) {}

    public function createPaymentIntent(Request $request): JsonResponse
    {
        $request->validate([
            'amount'            => 'required|numeric|min:1',
            'currency'          => 'required|string|size:3',
            'payment_method_id' => 'required|string|starts_with:pm_',
            'transaction_uuid'  => 'nullable|string',
        ]);

        $result = $this->stripeService->createPaymentIntent(
            user: $request->user(),
            amountInSourceCurrency: (float) $request->amount,
            sourceCurrency: strtoupper($request->currency),
            amountInCny: 0,
            paymentMethodId: $request->payment_method_id,
            metadata: $request->only('transaction_uuid'),
        );

        return response()->json(['data' => $result]);
    }
}
