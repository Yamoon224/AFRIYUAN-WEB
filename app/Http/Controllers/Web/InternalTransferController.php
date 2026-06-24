<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\InternalTransferService;
use App\Services\WalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InternalTransferController extends Controller
{
    public function __construct(
        private readonly InternalTransferService $transferService,
        private readonly WalletService $walletService,
    ) {}

    public function create(Request $request): View
    {
        $wallet = $this->walletService->getOrCreate($request->user());

        return view('transfers.internal', compact('wallet'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'receiver_uuid' => 'required|string|exists:users,uuid',
            'amount'        => 'required|numeric|min:1',
            'description'   => 'nullable|string|max:255',
        ]);

        $receiver = User::where('uuid', $request->receiver_uuid)->firstOrFail();

        $this->walletService->getOrCreate($receiver);

        try {
            $this->transferService->transfer(
                $request->user(),
                $receiver,
                (float) $request->amount,
                $request->description ?? '',
            );
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('wallet.index')->with('success', __('transfer.success'));
    }

    public function searchRecipient(Request $request)
    {
        $request->validate(['q' => 'required|string|min:3']);

        $users = User::where('id', '!=', $request->user()->id)
            ->where(function ($q) use ($request) {
                $q->where('email', 'like', "%{$request->q}%")
                  ->orWhere('phone_number', 'like', "%{$request->q}%");
            })
            ->select('uuid', 'first_name', 'last_name', 'email', 'phone_number')
            ->limit(5)
            ->get();

        return response()->json($users);
    }
}
