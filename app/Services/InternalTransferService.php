<?php

namespace App\Services;

use App\Models\AppNotification;
use App\Models\InternalTransfer;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class InternalTransferService
{
    public function __construct(private readonly WalletService $walletService) {}

    public function transfer(User $sender, User $receiver, float $amount, string $description = ''): InternalTransfer
    {
        if ($sender->id === $receiver->id) {
            throw new \InvalidArgumentException(__('transfer.self_transfer'));
        }

        return DB::transaction(function () use ($sender, $receiver, $amount, $description) {
            $senderWallet   = Wallet::lockForUpdate()->where('user_id', $sender->id)->firstOrFail();
            $receiverWallet = Wallet::lockForUpdate()->where('user_id', $receiver->id)->firstOrFail();

            if ((float) $senderWallet->balance < $amount) {
                throw new \RuntimeException(__('transfer.insufficient'));
            }

            $sBefore = (float) $senderWallet->balance;
            $senderWallet->update(['balance' => $sBefore - $amount]);

            WalletTransaction::create([
                'wallet_id'      => $senderWallet->id,
                'type'           => 'debit',
                'amount'         => $amount,
                'balance_before' => $sBefore,
                'balance_after'  => $sBefore - $amount,
                'description'    => $description ?: __('transfer.internal'),
                'source'         => 'internal_transfer',
            ]);

            $rBefore = (float) $receiverWallet->balance;
            $receiverWallet->update(['balance' => $rBefore + $amount]);

            WalletTransaction::create([
                'wallet_id'      => $receiverWallet->id,
                'type'           => 'credit',
                'amount'         => $amount,
                'balance_before' => $rBefore,
                'balance_after'  => $rBefore + $amount,
                'description'    => $description ?: __('transfer.internal'),
                'source'         => 'internal_transfer',
            ]);

            $transfer = InternalTransfer::create([
                'sender_id'          => $senderWallet->user_id,
                'receiver_id'        => $receiverWallet->user_id,
                'sender_wallet_id'   => $senderWallet->id,
                'receiver_wallet_id' => $receiverWallet->id,
                'amount'             => $amount,
                'currency_code'      => $senderWallet->currency_code,
                'description'        => $description,
                'status'             => 'completed',
            ]);

            $this->notify($senderWallet->user_id, "Vous avez envoyé {$amount} {$senderWallet->currency_code} à {$receiver->full_name}.");
            $this->notify($receiverWallet->user_id, "Vous avez reçu {$amount} {$senderWallet->currency_code} de {$senderWallet->user->full_name}.");

            return $transfer;
        });
    }

    private function notify(int $userId, string $message): void
    {
        AppNotification::create([
            'user_id' => $userId,
            'title'   => __('transfer.internal'),
            'body'    => $message,
            'type'    => 'internal_transfer',
        ]);
    }
}
