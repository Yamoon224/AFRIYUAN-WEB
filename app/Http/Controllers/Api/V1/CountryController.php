<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Country::with('currency')->where('is_active', true);

        if ($request->type === 'source') {
            $query->where('is_source', true);
        } elseif ($request->type === 'destination') {
            $query->where('is_destination', true);
        }

        $countries = $query->get()->map(fn ($c) => [
            'id'           => $c->id,
            'name'         => $c->name,
            'iso_code'     => $c->iso_code,
            'phone_prefix' => $c->phone_prefix,
            'flag_url'     => $c->flag_url,
            'is_source'    => $c->is_source,
            'is_destination' => $c->is_destination,
            'currency'     => [
                'code'   => $c->currency->code,
                'symbol' => $c->currency->symbol,
                'name'   => $c->currency->name,
            ],
        ]);

        return response()->json(['data' => $countries]);
    }
}
