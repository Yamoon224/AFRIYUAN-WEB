@extends('layouts.app')

@section('title', 'Historique des transferts')
@section('page-title', 'Historique des transferts')

@section('content')

{{-- Filters --}}
<div class="card mb-5" x-data="{ open: false }">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3 flex-wrap">
            <form method="GET" class="flex items-center gap-3 flex-wrap">
                <select name="status" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500">
                    <option value="">Tous les statuts</option>
                    @foreach(['completed'=>'Complété','processing'=>'En cours','failed'=>'Échoué','cancelled'=>'Annulé','refunded'=>'Remboursé'] as $v => $l)
                    <option value="{{ $v }}" {{ request('status') == $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>

                <select name="direction" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500">
                    <option value="">Toutes directions</option>
                    <option value="africa_to_china" {{ request('direction') === 'africa_to_china' ? 'selected' : '' }}>🌍 → 🇨🇳 Afrique → Chine</option>
                    <option value="china_to_africa" {{ request('direction') === 'china_to_africa' ? 'selected' : '' }}>🇨🇳 → 🌍 Chine → Afrique</option>
                </select>

                <input type="month" name="month" value="{{ request('month') }}" onchange="this.form.submit()"
                       class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-primary-500/30">

                @if(request()->hasAny(['status','direction','month']))
                <a href="{{ route('transfers.index') }}" class="text-xs text-gray-500 hover:text-red-500">✕ Réinitialiser</a>
                @endif
            </form>
        </div>

        <a href="{{ route('transfers.create') }}" class="btn-primary text-sm shrink-0">
            + Nouveau transfert
        </a>
    </div>
</div>

{{-- Transactions table --}}
<div class="card overflow-hidden p-0">
    @if($transactions->isEmpty())
    <div class="text-center py-16">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <p class="text-gray-500 font-medium">Aucune transaction trouvée</p>
        <a href="{{ route('transfers.create') }}" class="mt-3 inline-block btn-primary text-sm">
            Faire un transfert
        </a>
    </div>
    @else
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                <th class="px-6 py-4">Référence</th>
                <th class="px-4 py-4">Direction</th>
                <th class="px-4 py-4">Montant envoyé</th>
                <th class="px-4 py-4">Reçu</th>
                <th class="px-4 py-4">Bénéficiaire</th>
                <th class="px-4 py-4">Méthode</th>
                <th class="px-4 py-4">Statut</th>
                <th class="px-4 py-4">Date</th>
                <th class="px-4 py-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($transactions as $tx)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4">
                    <span class="font-mono text-xs text-gray-600">{{ $tx->reference_number }}</span>
                </td>
                <td class="px-4 py-4">
                    @if($tx->direction === 'africa_to_china')
                    <span class="text-xs font-medium text-orange-600 bg-orange-50 px-2 py-1 rounded-lg">🌍→🇨🇳</span>
                    @else
                    <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">🇨🇳→🌍</span>
                    @endif
                </td>
                <td class="px-4 py-4 font-semibold text-gray-900">
                    {{ $tx->send_currency_symbol }} {{ number_format($tx->send_amount, 0, '.', ' ') }}
                </td>
                <td class="px-4 py-4 font-semibold text-primary-600">
                    {{ $tx->receive_currency === 'CNY' ? '¥' : '' }} {{ number_format($tx->receive_amount, 2, '.', ' ') }} {{ $tx->receive_currency }}
                </td>
                <td class="px-4 py-4 text-gray-700">{{ $tx->beneficiary?->nickname ?? '—' }}</td>
                <td class="px-4 py-4">
                    <span class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $tx->payment_method)) }}</span>
                </td>
                <td class="px-4 py-4">
                    @switch($tx->status)
                        @case('completed') <span class="badge-success">Complété</span> @break
                        @case('processing')
                        @case('payment_confirmed')
                        @case('sent_to_beneficiary') <span class="badge-info">En cours</span> @break
                        @case('failed') <span class="badge-danger">Échoué</span> @break
                        @case('cancelled') <span class="badge-gray">Annulé</span> @break
                        @case('refunded') <span class="badge-warning">Remboursé</span> @break
                        @default <span class="badge-gray">{{ ucfirst($tx->status) }}</span>
                    @endswitch
                </td>
                <td class="px-4 py-4 text-gray-400 text-xs whitespace-nowrap">
                    {{ $tx->created_at->format('d/m/Y') }}<br>
                    <span class="text-gray-300">{{ $tx->created_at->format('H:i') }}</span>
                </td>
                <td class="px-4 py-4">
                    <a href="{{ route('transfers.show', $tx->uuid) }}"
                       class="text-primary-600 hover:text-primary-700 text-xs font-medium">Détails →</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($transactions->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $transactions->withQueryString()->links() }}
    </div>
    @endif
    @endif
</div>
@endsection
