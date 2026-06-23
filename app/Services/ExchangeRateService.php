<?php

namespace App\Services;

use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Cache;

class ExchangeRateService
{
    private const CACHE_TTL_SECONDS = 300; // 5 min — rates change only when admin updates them

    public function getRate(string $from, string $to): ?ExchangeRate
    {
        $cacheKey = "exchange_rate_{$from}_{$to}";

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($from, $to) {
            return ExchangeRate::current($from, $to)->first();
        });
    }

    public function quote(string $from, string $to, float $amount): array
    {
        $rateModel = $this->getRate($from, $to);

        // If no admin-defined rate exists, fall back to static reference rates
        $rate     = $rateModel?->margin_rate ?? $this->staticRate($from, $to);
        $market   = $rateModel?->rate         ?? $rate;
        $margin   = $rateModel?->margin_percent ?? 2.50;

        return [
            'from_currency'   => $from,
            'to_currency'     => $to,
            'send_amount'     => $amount,
            'receive_amount'  => round($amount * $rate, 2),
            'exchange_rate'   => $rate,
            'market_rate'     => $market,
            'margin_percent'  => $margin,
            'rate_expires_at' => $rateModel?->expires_at?->toISOString(),
            'rate_id'         => $rateModel?->id,
            'source'          => $rateModel ? 'backoffice' : 'static_fallback',
        ];
    }

    public function forgetCache(string $from, string $to): void
    {
        Cache::forget("exchange_rate_{$from}_{$to}");
    }

    /**
     * Static reference rates (USD-relative) used only when the admin has not
     * yet configured a rate for a given pair. These are indicative only.
     */
    private function staticRate(string $from, string $to): float
    {
        $vsUsd = [
            'XOF' => 600.0,  'XAF' => 600.0,  'GNF' => 8600.0,
            'GHS' => 15.5,   'LRD' => 190.0,  'SLE' => 22.5,
            'CNY' => 7.25,   'USD' => 1.0,    'EUR' => 0.92,
        ];

        $fromRate = $vsUsd[$from] ?? 1.0;
        $toRate   = $vsUsd[$to]   ?? 1.0;

        return round($toRate / $fromRate, 8);
    }
}
