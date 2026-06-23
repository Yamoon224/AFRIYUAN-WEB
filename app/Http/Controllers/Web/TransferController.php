<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->transactions()->with('beneficiary')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('direction')) {
            $query->where('direction', $request->direction);
        }
        if ($request->filled('month')) {
            [$year, $month] = explode('-', $request->month);
            $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
        }

        $transactions = $query->paginate(15);
        return view('transfers.index', compact('transactions'));
    }

    public function create(Request $request)
    {
        $preselectedBeneficiary = $request->filled('beneficiary') ? $request->beneficiary : null;
        return view('transfers.create', compact('preselectedBeneficiary'));
    }

    public function show(string $uuid)
    {
        $transaction = Auth::user()->transactions()
            ->with(['beneficiary', 'statusLogs'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        return view('transfers.show', compact('transaction'));
    }

    public function cancel(string $uuid)
    {
        $transaction = Auth::user()->transactions()
            ->where('uuid', $uuid)
            ->firstOrFail();

        if (!$transaction->canBeCancelled()) {
            return back()->with('error', 'Ce transfert ne peut pas être annulé.');
        }

        $transaction->update(['status' => 'cancelled']);
        return redirect()->route('transfers.index')->with('success', 'Transfert annulé avec succès.');
    }
}
