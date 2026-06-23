<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('country')->withCount('transactions')->latest();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }
        if ($request->filled('kyc_status'))     $query->where('kyc_status', $request->kyc_status);
        if ($request->filled('account_status')) $query->where('account_status', $request->account_status);

        $users = $query->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show(string $uuid)
    {
        $user = User::with(['country', 'kycDocuments', 'transactions' => fn($q) => $q->latest()->limit(10), 'cards'])->where('uuid', $uuid)->firstOrFail();
        return view('admin.users.show', compact('user'));
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate(['account_status' => 'required|in:active,suspended,closed']);
        $user->update(['account_status' => $request->account_status]);
        return back()->with('success', 'Statut du compte mis à jour.');
    }
}
