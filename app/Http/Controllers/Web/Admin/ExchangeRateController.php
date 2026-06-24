<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use App\Models\TransferFee;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function __construct(private ExchangeRateService $rateService) {}

    public function index()
    {
        $rates       = ExchangeRate::orderBy('from_currency')->get();
        $fees        = TransferFee::orderBy('from_currency')->get();
        $lastUpdated = ExchangeRate::latest('fetched_at')->value('fetched_at');

        return view('admin.exchange-rates.index', compact('rates', 'fees', 'lastUpdated'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'from_currency'  => 'required|string|size:3',
            'to_currency'    => 'required|string|size:3|different:from_currency',
            'rate'           => 'required|numeric|min:0.00000001',
            'margin_percent' => 'required|numeric|min:0|max:20',
        ]);

        $marginRate = $data['rate'] * (1 - $data['margin_percent'] / 100);

        ExchangeRate::updateOrCreate(
            ['from_currency' => $data['from_currency'], 'to_currency' => $data['to_currency']],
            [
                'rate'           => $data['rate'],
                'margin_rate'    => $marginRate,
                'margin_percent' => $data['margin_percent'],
                'fetched_at'     => now(),
                'expires_at'     => now()->addYears(10),
            ]
        );

        // Vider le cache pour ce pair
        $this->rateService->forgetCache($data['from_currency'], $data['to_currency']);

        return redirect()->route('admin.exchange-rates.index')
            ->with('success', "Taux {$data['from_currency']} → {$data['to_currency']} enregistré.");
    }

    public function destroy(ExchangeRate $exchangeRate)
    {
        $this->rateService->forgetCache($exchangeRate->from_currency, $exchangeRate->to_currency);
        $exchangeRate->delete();

        return redirect()->route('admin.exchange-rates.index')
            ->with('success', 'Taux supprimé.');
    }
}
