<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UserCard;
use App\Services\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function __construct(private readonly StripeService $stripeService) {}

    public function index(Request $request): JsonResponse
    {
        $cards = $request->user()
            ->cards()
            ->active()
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (UserCard $card) => [
                'id'                => $card->id,
                'display_name'      => $card->display_name,
                'card_brand'        => $card->card_brand,
                'last_four'         => $card->last_four,
                'expiry'            => $card->expiry,
                'cardholder_name'   => $card->cardholder_name,
                'funding'           => $card->funding,
                'is_default'        => $card->is_default,
                'is_expired'        => $card->isExpired(),
                'three_d_secure'    => $card->three_d_secure_usage,
                'last_used_at'      => $card->last_used_at?->toISOString(),
            ]);

        return response()->json(['data' => $cards]);
    }

    public function setupIntent(Request $request): JsonResponse
    {
        $intent = $this->stripeService->createSetupIntent($request->user());
        return response()->json(['data' => $intent]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'payment_method_id' => 'required|string|starts_with:pm_',
        ]);

        $card = $this->stripeService->attachPaymentMethod(
            $request->user(),
            $request->payment_method_id
        );

        return response()->json([
            'message' => 'Card added successfully.',
            'data' => [
                'id'            => $card->id,
                'display_name'  => $card->display_name,
                'card_brand'    => $card->card_brand,
                'last_four'     => $card->last_four,
                'expiry'        => $card->expiry,
                'is_default'    => $card->is_default,
            ],
        ], 201);
    }

    public function setDefault(Request $request, UserCard $card): JsonResponse
    {
        abort_if($card->user_id !== $request->user()->id, 403);

        $request->user()->cards()->update(['is_default' => false]);
        $card->update(['is_default' => true]);

        return response()->json(['message' => 'Default card updated.']);
    }

    public function destroy(Request $request, UserCard $card): JsonResponse
    {
        abort_if($card->user_id !== $request->user()->id, 403);

        $this->stripeService->detachPaymentMethod($card);

        // Set another card as default if this was the default
        if ($card->is_default) {
            $request->user()->cards()->active()
                ->where('id', '!=', $card->id)
                ->latest()
                ->first()
                ?->update(['is_default' => true]);
        }

        return response()->json(['message' => 'Card removed successfully.']);
    }
}
