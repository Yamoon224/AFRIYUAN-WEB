<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function __construct(private readonly WalletService $walletService) {}

    public function index(Request $request): View
    {
        $wallet = $this->walletService->getOrCreate($request->user());

        $transactions = $wallet->transactions()->latest()->paginate(15);

        return view('wallet.index', compact('wallet', 'transactions'));
    }

    public function topUp(Request $request): RedirectResponse
    {
        $request->validate([
            'amount'      => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $wallet = $this->walletService->getOrCreate($request->user());

        if (!$wallet->isActive()) {
            return back()->with('error', 'Votre wallet est inactif.');
        }

        $this->walletService->topUp($wallet, (float) $request->amount, $request->description ?? '');

        return back()->with('success', __('wallet.topup_success'));
    }

    public function withdraw(Request $request): RedirectResponse
    {
        $request->validate([
            'amount'      => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $wallet = $this->walletService->getOrCreate($request->user());

        try {
            $this->walletService->withdraw($wallet, (float) $request->amount, $request->description ?? '');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Retrait effectué avec succès.');
    }
}
