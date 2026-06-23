<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'beneficiary'])->latest();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('email', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%"));
            });
        }
        if ($request->filled('status'))     $query->where('status', $request->status);
        if ($request->filled('direction'))  $query->where('direction', $request->direction);
        if ($request->filled('compliance')) $query->where('compliance_status', $request->compliance);

        $transactions = $query->paginate(20);
        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(string $uuid)
    {
        $transaction = Transaction::with(['user', 'beneficiary', 'statusLogs'])->where('uuid', $uuid)->firstOrFail();
        return view('admin.transactions.show', compact('transaction'));
    }

    public function updateCompliance(Request $request, Transaction $transaction)
    {
        $request->validate(['compliance_status' => 'required|in:flagged,cleared,under_review']);
        $transaction->update(['compliance_status' => $request->compliance_status]);
        return back()->with('success', 'Statut compliance mis à jour.');
    }
}
