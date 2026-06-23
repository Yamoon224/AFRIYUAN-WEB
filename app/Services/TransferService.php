<?php

namespace App\Services;

use App\Models\Beneficiary;
use App\Models\Transaction;
use App\Models\TransferFee;
use App\Models\TransferLimit;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransferService
{
    public function __construct(
        private readonly ExchangeRateService $exchangeRateService
    ) {}

    public function quote(array $data): array
    {
        $from   = strtoupper($data['from_currency']);
        $to     = strtoupper($data['to_currency']);
        $amount = (float) $data['send_amount'];

        $direction = $this->resolveDirection($from, $to);
        $quote     = $this->exchangeRateService->quote($from, $to, $amount);
        $fee       = $this->calculateFee($from, $to, $amount);

        $totalDebit = $amount + $fee;

        return array_merge($quote, [
            'direction'         => $direction,
            'fee_amount'        => $fee,
            'fee_currency'      => $from,
            'total_debit'       => $totalDebit,
            'total_debit_label' => $this->formatAmount($totalDebit, $from),
            'receive_label'     => $this->formatAmount($quote['receive_amount'], $to),
        ]);
    }

    public function create(User $user, array $data): Transaction
    {
        return DB::transaction(function () use ($user, $data) {
            $from      = strtoupper($data['from_currency']);
            $to        = strtoupper($data['to_currency']);
            $amount    = (float) $data['send_amount'];
            $direction = $this->resolveDirection($from, $to);

            $quote = $this->exchangeRateService->quote($from, $to, $amount);
            $fee   = $this->calculateFee($from, $to, $amount);

            $transaction = Transaction::create([
                'user_id'          => $user->id,
                'beneficiary_id'   => $data['beneficiary_id'],
                'direction'        => $direction,
                'send_amount'      => $amount,
                'send_currency'    => $from,
                'send_currency_symbol' => $this->currencySymbol($from),
                'exchange_rate_id' => $quote['rate_id'],
                'applied_rate'     => $quote['exchange_rate'],
                'fee_amount'       => $fee,
                'fee_currency'     => $from,
                'total_debit_amount' => $amount + $fee,
                'receive_amount'   => $quote['receive_amount'],
                'receive_currency' => $to,
                'payment_method'   => $data['payment_method'],
                'receive_method'   => $data['receive_method'],
                'status'           => 'initiated',
                'initiated_at'     => now(),
                'source_ip'        => request()->ip(),
                'user_agent'       => request()->userAgent(),
            ]);

            $transaction->statusLogs()->create([
                'from_status'     => '',
                'to_status'       => 'initiated',
                'changed_by_type' => 'user',
                'changed_by_id'   => $user->id,
            ]);

            return $transaction;
        });
    }

    private function calculateFee(string $from, string $to, float $amount): float
    {
        $feeModel = TransferFee::where('from_currency', $from)
            ->where('to_currency', $to)
            ->where('min_amount', '<=', $amount)
            ->where('max_amount', '>=', $amount)
            ->where('is_active', true)
            ->first();

        if (!$feeModel) return 0.0;

        return $feeModel->calculate($amount);
    }

    private function resolveDirection(string $from, string $to): string
    {
        if ($from === 'CNY') return 'china_to_africa';
        if ($to === 'CNY')   return 'africa_to_china';
        return 'africa_to_china';
    }

    private function currencySymbol(string $code): string
    {
        return match ($code) {
            'XOF', 'XAF' => 'CFA',
            'GNF' => 'FG',
            'GHS' => 'GH₵',
            'LRD' => 'L$',
            'SLE' => 'Le',
            'CNY' => '¥',
            default => $code,
        };
    }

    private function formatAmount(float $amount, string $currency): string
    {
        $symbol   = $this->currencySymbol($currency);
        $decimals = in_array($currency, ['XOF', 'XAF', 'GNF']) ? 0 : 2;
        return $symbol . ' ' . number_format($amount, $decimals, '.', ',');
    }

    public function validateLimit(User $user, float $amount, string $currency): bool
    {
        $limit = TransferLimit::where('kyc_level', '<=', $user->kyc_level)
            ->where('period', 'per_transaction')
            ->whereHas('currency', fn ($q) => $q->where('code', $currency))
            ->where('is_active', true)
            ->first();

        if (!$limit) return true;

        return $amount >= $limit->min_amount && $amount <= $limit->max_amount;
    }
}
