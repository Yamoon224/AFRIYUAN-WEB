<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\MobileMoneyAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileMoneyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $accounts = $request->user()->mobileMoneyAccounts()->with('country')->get();
        return response()->json(['data' => $accounts]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'provider'     => 'required|string|in:orange_money,mtn_momo,moov,airtel,wave,free_money',
            'phone_number' => 'required|string|max:20',
            'country_id'   => 'required|exists:countries,id',
        ]);

        if ($request->user()->mobileMoneyAccounts()->where('is_default', true)->exists()) {
            $isDefault = false;
        } else {
            $isDefault = true;
        }

        $account = $request->user()->mobileMoneyAccounts()->create([
            ...$request->only(['provider', 'phone_number', 'country_id']),
            'is_default' => $isDefault,
        ]);

        return response()->json(['message' => 'Account added.', 'data' => $account->load('country')], 201);
    }

    public function destroy(Request $request, MobileMoneyAccount $mobileMoneyAccount): JsonResponse
    {
        abort_if($mobileMoneyAccount->user_id !== $request->user()->id, 403);
        $mobileMoneyAccount->delete();
        return response()->json(['message' => 'Account removed.']);
    }
}
