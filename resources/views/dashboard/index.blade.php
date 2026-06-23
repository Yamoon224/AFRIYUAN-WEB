@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')
@section('breadcrumb', 'Bienvenue, ' . auth()->user()->first_name)

@section('content')

{{-- KYC Alert --}}
@if(auth()->user()->kyc_status !== 'approved')
<div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-2xl flex items-center gap-4">
    <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center shrink-0">
        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
    </div>
    <div class="flex-1">
        <p class="text-sm font-semibold text-yellow-800">Vérification d'identité requise</p>
        <p class="text-xs text-yellow-600">Complétez votre KYC pour débloquer les transferts.</p>
    </div>
    <a href="{{ route('kyc.index') }}" class="btn-primary text-xs px-4 py-2 bg-yellow-500 hover:bg-yellow-600">
        Vérifier maintenant
    </a>
</div>
@endif

{{-- Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500 font-medium">Total envoyé</p>
            <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_sent'] ?? '—' }}</p>
        <p class="text-xs text-gray-400">{{ $stats['transactions_count'] ?? 0 }} transfert(s)</p>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500 font-medium">Ce mois</p>
            <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['monthly_sent'] ?? '—' }}</p>
        <p class="text-xs text-gray-400">{{ now()->format('F Y') }}</p>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500 font-medium">Bénéficiaires</p>
            <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['beneficiaries_count'] ?? 0 }}</p>
        <p class="text-xs text-gray-400">destinataires actifs</p>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500 font-medium">Statut KYC</p>
            <div class="w-9 h-9 bg-{{ auth()->user()->kyc_status === 'approved' ? 'green' : 'yellow' }}-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-{{ auth()->user()->kyc_status === 'approved' ? 'green' : 'yellow' }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
        </div>
        <p class="text-lg font-bold text-gray-900 capitalize">
            @switch(auth()->user()->kyc_status)
                @case('approved') <span class="text-green-600">Approuvé</span> @break
                @case('under_review') <span class="text-yellow-600">En cours</span> @break
                @case('rejected') <span class="text-red-600">Rejeté</span> @break
                @default <span class="text-gray-400">Non vérifié</span>
            @endswitch
        </p>
        <p class="text-xs text-gray-400">Niveau {{ auth()->user()->kyc_level }}</p>
    </div>
</div>

{{-- Live exchange rates + Quick send --}}
<div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">

    {{-- Exchange rates --}}
    <div class="card lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-semibold text-gray-900">Taux en direct</h3>
            <span class="text-xs text-gray-400 flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse inline-block"></span>
                Mis à jour
            </span>
        </div>
        <div class="space-y-3">
            @foreach($rates ?? [] as $rate)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-700">{{ $rate['from'] }}</span>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    <span class="text-sm font-medium text-gray-700">{{ $rate['to'] }}</span>
                </div>
                <span class="text-sm font-bold text-primary-600">{{ $rate['rate'] }}</span>
            </div>
            @endforeach
            @if(empty($rates))
            @foreach([['XOF','CNY','0.0120'],['GHS','CNY','0.4700'],['GNF','CNY','0.0008'],['LRD','CNY','0.0380']] as $r)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-700">{{ $r[0] }}</span>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    <span class="text-sm font-medium text-gray-700">{{ $r[1] }}</span>
                </div>
                <span class="text-sm font-bold text-primary-600">{{ $r[2] }}</span>
            </div>
            @endforeach
            @endif
        </div>
    </div>

    {{-- Quick send --}}
    <div class="card lg:col-span-3 bg-gradient-to-br from-primary-500 to-primary-700 text-white border-0">
        <h3 class="text-base font-semibold mb-1">Envoi rapide</h3>
        <p class="text-primary-200 text-xs mb-4">Calculez instantanément ce que votre destinataire reçoit.</p>

        <div class="bg-white/10 rounded-xl p-4 space-y-3" x-data="quickSend()">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs text-primary-200 mb-1 block">J'envoie</label>
                    <div class="flex gap-2">
                        <input type="number" x-model="amount" @input="calculate()"
                               placeholder="100 000" min="0"
                               class="flex-1 bg-white/20 border border-white/30 rounded-lg px-3 py-2 text-white placeholder-white/50 text-sm focus:outline-none focus:border-white">
                        <select x-model="fromCurrency" @change="calculate()"
                                class="bg-white/20 border border-white/30 rounded-lg px-2 py-2 text-white text-sm focus:outline-none focus:border-white">
                            <option class="text-gray-900" value="XOF">XOF</option>
                            <option class="text-gray-900" value="XAF">XAF</option>
                            <option class="text-gray-900" value="GHS">GHS</option>
                            <option class="text-gray-900" value="GNF">GNF</option>
                            <option class="text-gray-900" value="LRD">LRD</option>
                            <option class="text-gray-900" value="SLE">SLE</option>
                            <option class="text-gray-900" value="CNY">CNY</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-xs text-primary-200 mb-1 block">Destinataire reçoit</label>
                    <div class="bg-white/20 border border-white/30 rounded-lg px-3 py-2 text-white text-sm font-bold">
                        <span x-text="receiveAmount || '—'"></span>
                        <span class="text-white/70 text-xs font-normal ml-1" x-text="toCurrency"></span>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between text-xs text-primary-200">
                <span>Taux: <span x-text="rate || '—'" class="font-medium text-white"></span></span>
                <span>Frais: <span x-text="fee || '—'" class="font-medium text-white"></span></span>
            </div>
            <a href="{{ route('transfers.create') }}" class="block w-full text-center bg-white text-primary-600 font-bold rounded-xl py-2.5 text-sm hover:bg-primary-50 transition-colors">
                Envoyer maintenant →
            </a>
        </div>
    </div>
</div>

{{-- Recent transactions --}}
<div class="card">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-semibold text-gray-900">Transactions récentes</h3>
        <a href="{{ route('transfers.index') }}" class="text-sm text-primary-600 hover:underline font-medium">Voir tout</a>
    </div>

    @if(($transactions ?? collect())->isEmpty())
    <div class="text-center py-10">
        <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <p class="text-gray-500 text-sm font-medium">Aucune transaction pour l'instant</p>
        <a href="{{ route('transfers.create') }}" class="mt-3 inline-block btn-primary text-sm">
            Faire un premier transfert
        </a>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs font-medium text-gray-500 border-b border-gray-100">
                    <th class="pb-3 pr-4">Référence</th>
                    <th class="pb-3 pr-4">Bénéficiaire</th>
                    <th class="pb-3 pr-4">Montant</th>
                    <th class="pb-3 pr-4">Direction</th>
                    <th class="pb-3 pr-4">Statut</th>
                    <th class="pb-3">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($transactions as $tx)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="py-3 pr-4">
                        <a href="{{ route('transfers.show', $tx->uuid) }}"
                           class="font-mono text-xs text-primary-600 hover:underline">{{ $tx->reference_number }}</a>
                    </td>
                    <td class="py-3 pr-4 text-gray-700">{{ $tx->beneficiary?->nickname ?? '—' }}</td>
                    <td class="py-3 pr-4 font-semibold text-gray-900">
                        {{ $tx->send_currency_symbol }} {{ number_format($tx->send_amount, 0, '.', ' ') }}
                    </td>
                    <td class="py-3 pr-4">
                        <span class="text-xs {{ $tx->direction === 'africa_to_china' ? 'text-orange-600' : 'text-blue-600' }}">
                            {{ $tx->direction === 'africa_to_china' ? '🌍→🇨🇳' : '🇨🇳→🌍' }}
                        </span>
                    </td>
                    <td class="py-3 pr-4">
                        @switch($tx->status)
                            @case('completed') <span class="badge-success">Complété</span> @break
                            @case('processing') <span class="badge-info">En cours</span> @break
                            @case('failed') <span class="badge-danger">Échoué</span> @break
                            @case('cancelled') <span class="badge-gray">Annulé</span> @break
                            @default <span class="badge-warning">{{ ucfirst($tx->status) }}</span>
                        @endswitch
                    </td>
                    <td class="py-3 text-gray-400 text-xs">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@push('scripts')
<script>
function quickSend() {
    return {
        amount: '',
        fromCurrency: 'XOF',
        toCurrency: 'CNY',
        receiveAmount: '',
        rate: '',
        fee: '',
        debounce: null,
        calculate() {
            clearTimeout(this.debounce);
            this.debounce = setTimeout(async () => {
                if (!this.amount || this.amount < 1) return;
                try {
                    const to = this.fromCurrency === 'CNY'
                        ? (document.querySelector('select[name=to_currency]')?.value || 'XOF')
                        : 'CNY';
                    this.toCurrency = to;
                    const res = await fetch(`/api/v1/transfers/quote`, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
                        body: JSON.stringify({from_currency: this.fromCurrency, to_currency: to, send_amount: this.amount})
                    });
                    const data = await res.json();
                    if (data.data) {
                        this.receiveAmount = new Intl.NumberFormat('fr-FR').format(data.data.receive_amount);
                        this.rate = data.data.exchange_rate.toFixed(6);
                        this.fee = data.data.fee_amount ? new Intl.NumberFormat('fr-FR').format(data.data.fee_amount) + ' ' + this.fromCurrency : '0';
                    }
                } catch(e) {}
            }, 400);
        }
    }
}
</script>
@endpush
@endsection
