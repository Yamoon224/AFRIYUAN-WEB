<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ExchangeRateService;
use Illuminate\Http\JsonResponse;

class ExchangeRateController extends Controller
{
    public function __construct(private readonly ExchangeRateService $service) {}

    public function show(string $from, string $to): JsonResponse
    {
        $from = strtoupper($from);
        $to   = strtoupper($to);

        $rate = $this->service->getRate($from, $to);

        if (!$rate) {
            return response()->json(['message' => 'Rate not available for this pair.'], 404);
        }

        return response()->json(['data' => [
            'from_currency'  => $rate->from_currency,
            'to_currency'    => $rate->to_currency,
            'rate'           => (float) $rate->rate,
            'margin_rate'    => (float) $rate->margin_rate,
            'margin_percent' => (float) $rate->margin_percent,
            'expires_at'     => $rate->expires_at?->toISOString(),
        ]]);
    }

    public function index(): JsonResponse
    {
        $pairs = [
            ['XOF','CNY'], ['XAF','CNY'], ['GNF','CNY'],
            ['GHS','CNY'], ['LRD','CNY'], ['SLE','CNY'],
            ['CNY','XOF'], ['CNY','XAF'], ['CNY','GHS'],
        ];

        $rates = collect($pairs)->map(function ($pair) {
            $r = $this->service->getRate($pair[0], $pair[1]);
            if (!$r) return null;
            return [
                'pair'           => "{$pair[0]}/{$pair[1]}",
                'from_currency'  => $r->from_currency,
                'to_currency'    => $r->to_currency,
                'margin_rate'    => (float) $r->margin_rate,
                'expires_at'     => $r->expires_at?->toISOString(),
            ];
        })->filter()->values();

        return response()->json(['data' => $rates]);
    }
}
