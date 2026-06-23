<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
    public function index(): JsonResponse
    {
        $currencies = Currency::where('is_active', true)->get()->map(fn ($c) => [
            'code'           => $c->code,
            'name'           => $c->name,
            'symbol'         => $c->symbol,
            'decimal_places' => $c->decimal_places,
        ]);

        return response()->json(['data' => $currencies]);
    }
}
