<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function getOrCreate(User $user): Wallet
    {
        return $user->wallet ?? Wallet::create([
            'user_id'       => $user->id,
            'currency_code' => $user->preferredCurrency?->code ?? 'XOF',
            'balance'       => 0,
        ]);
    }

    public function topUp(Wallet $wallet, float $amount, string $description = '', string $source = 'topup'): WalletTransaction
    {
        return DB::transaction(function () use ($wallet, $amount, $description, $source) {
            $wallet = Wallet::lockForUpdate()->find($wallet->id);

            $before = (float) $wallet->balance;
            $after  = $before + $amount;

            $wallet->update(['balance' => $after]);

            return WalletTransaction::create([
                'wallet_id'      => $wallet->id,
                'type'           => 'credit',
                'amount'         => $amount,
                'balance_before' => $before,
                'balance_after'  => $after,
                'description'    => $description ?: __('wallet.topup'),
                'source'         => $source,
            ]);
        });
    }

    public function withdraw(Wallet $wallet, float $amount, string $description = '', string $source = 'withdraw'): WalletTransaction
    {
        return DB::transaction(function () use ($wallet, $amount, $description, $source) {
            $wallet = Wallet::lockForUpdate()->find($wallet->id);

            if ((float) $wallet->balance < $amount) {
                throw new \RuntimeException(__('wallet.insufficient'));
            }

            $before = (float) $wallet->balance;
            $after  = $before - $amount;

            $wallet->update(['balance' => $after]);

            return WalletTransaction::create([
                'wallet_id'      => $wallet->id,
                'type'           => 'debit',
                'amount'         => $amount,
                'balance_before' => $before,
                'balance_after'  => $after,
                'description'    => $description ?: __('wallet.withdraw'),
                'source'         => $source,
            ]);
        });
    }
}
