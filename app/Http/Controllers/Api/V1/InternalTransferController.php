<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\InternalTransferService;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InternalTransferController extends Controller
{
    public function __construct(
        private readonly InternalTransferService $transferService,
        private readonly WalletService $walletService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $transfers = $user->sentInternalTransfers()
            ->with(['receiver:id,first_name,last_name,email'])
            ->latest()
            ->paginate(20);

        return response()->json($transfers);
    }

    public function searchRecipient(Request $request): JsonResponse
    {
        $request->validate(['q' => 'required|string|min:3']);

        $users = User::where('id', '!=', $request->user()->id)
            ->where(function ($query) use ($request) {
                $query->where('email', 'like', "%{$request->q}%")
                    ->orWhere('phone_number', 'like', "%{$request->q}%");
            })
            ->select('id', 'uuid', 'first_name', 'last_name', 'email', 'phone_number')
            ->limit(5)
            ->get();

        return response()->json(['data' => $users]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'receiver_uuid' => 'required|string|exists:users,uuid',
            'amount'        => 'required|numeric|min:0.01',
            'description'   => 'nullable|string|max:255',
        ]);

        $receiver = User::where('uuid', $request->receiver_uuid)->firstOrFail();

        $this->walletService->getOrCreate($receiver);

        try {
            $transfer = $this->transferService->transfer(
                $request->user(),
                $receiver,
                (float) $request->amount,
                $request->description ?? '',
            );
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'message' => __('transfer.success'),
            'data'    => ['uuid' => $transfer->uuid],
        ], 201);
    }
}
