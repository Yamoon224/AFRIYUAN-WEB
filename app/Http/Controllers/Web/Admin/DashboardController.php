<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'today_count'     => Transaction::whereDate('created_at', today())->count(),
            'today_volume'    => Transaction::whereDate('created_at', today())->where('status', 'completed')->sum('send_amount'),
            'month_count'     => Transaction::whereMonth('created_at', now()->month)->count(),
            'month_volume_cny'=> Transaction::whereMonth('created_at', now()->month)->where('status', 'completed')->sum('receive_amount'),
            'active_users'    => User::where('account_status', 'active')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'kyc_pending'     => \App\Models\KycDocument::where('status', 'under_review')->count(),
            'africa_to_china' => $this->directionPercent('africa_to_china'),
            'china_to_africa' => $this->directionPercent('china_to_africa'),
        ];

        $recentTransactions = Transaction::with('user')->latest()->limit(10)->get();
        $flaggedCount        = Transaction::where('compliance_status', 'flagged')->count();

        return view('admin.dashboard', compact('stats', 'recentTransactions', 'flaggedCount'));
    }

    private function directionPercent(string $direction): int
    {
        $total = Transaction::count();
        if ($total === 0) return 0;
        return (int) round(Transaction::where('direction', $direction)->count() / $total * 100);
    }
}
