<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load(['transactions' => fn($q) => $q->latest()->limit(5), 'country']);

        $totalSent    = $user->transactions()->where('status', 'completed')->sum('send_amount');
        $monthSent    = $user->transactions()->where('status', 'completed')
                             ->whereMonth('created_at', now()->month)->sum('send_amount');
        $benefCount   = $user->beneficiaries()->count();

        $rates = ExchangeRate::with(['fromCurrency', 'toCurrency'])
            ->where('to_currency', 'CNY')
            ->where('expires_at', '>', now())
            ->get();

        return view('dashboard.index', compact('user', 'totalSent', 'monthSent', 'benefCount', 'rates'));
    }
}
