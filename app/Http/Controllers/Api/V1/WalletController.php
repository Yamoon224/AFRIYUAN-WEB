<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(private readonly WalletService $walletService) {}

    public function show(Request $request): JsonResponse
    {
        $wallet = $this->walletService->getOrCreate($request->user());

        return response()->json([
            'data' => [
                'uuid'          => $wallet->uuid,
                'balance'       => (float) $wallet->balance,
                'currency_code' => $wallet->currency_code,
                'status'        => $wallet->status,
            ],
        ]);
    }

    public function transactions(Request $request): JsonResponse
    {
        $wallet = $this->walletService->getOrCreate($request->user());

        $transactions = $wallet->transactions()
            ->latest()
            ->paginate(20);

        return response()->json($transactions);
    }

    public function topUp(Request $request): JsonResponse
    {
        $request->validate([
            'amount'      => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        $wallet = $this->walletService->getOrCreate($request->user());

        if (!$wallet->isActive()) {
            return response()->json(['message' => 'Wallet inactif.'], 422);
        }

        $this->walletService->topUp($wallet, (float) $request->amount, $request->description ?? '');

        $wallet->refresh();

        return response()->json([
            'message' => __('wallet.topup_success'),
            'balance' => (float) $wallet->balance,
        ]);
    }

    public function withdraw(Request $request): JsonResponse
    {
        $request->validate([
            'amount'      => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        $wallet = $this->walletService->getOrCreate($request->user());

        if (!$wallet->isActive()) {
            return response()->json(['message' => 'Wallet inactif.'], 422);
        }

        try {
            $this->walletService->withdraw($wallet, (float) $request->amount, $request->description ?? '');
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $wallet->refresh();

        return response()->json([
            'message' => 'Retrait effectué.',
            'balance' => (float) $wallet->balance,
        ]);
    }
}
