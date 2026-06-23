@extends('layouts.admin')

@section('title', 'Transactions')
@section('page-title', 'Gestion des transactions')

@section('content')
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    {{-- Filters --}}
    <div class="px-6 py-4 border-b border-gray-100">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Référence, email..."
                   class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-primary-500/30 w-48">

            <select name="status" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-xl px-3 py-2">
                <option value="">Tous statuts</option>
                @foreach(['pending','processing','payment_confirmed','sent_to_beneficiary','completed','failed','cancelled','refunded','on_hold'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>

            <select name="direction" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-xl px-3 py-2">
                <option value="">Toutes directions</option>
                <option value="africa_to_china" {{ request('direction') === 'africa_to_china' ? 'selected' : '' }}>🌍→🇨🇳</option>
                <option value="china_to_africa" {{ request('direction') === 'china_to_africa' ? 'selected' : '' }}>🇨🇳→🌍</option>
            </select>

            <select name="compliance" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-xl px-3 py-2">
                <option value="">Compliance</option>
                <option value="flagged" {{ request('compliance') === 'flagged' ? 'selected' : '' }}>Flagguée</option>
                <option value="cleared" {{ request('compliance') === 'cleared' ? 'selected' : '' }}>Validée</option>
            </select>

            <button type="submit" class="btn-primary text-sm">Filtrer</button>
            @if(request()->hasAny(['q','status','direction','compliance']))
            <a href="{{ route('admin.transactions.index') }}" class="text-xs text-gray-400 hover:text-gray-600">✕ Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wide">
            <tr>
                <th class="px-6 py-3 text-left">Référence</th>
                <th class="px-4 py-3 text-left">Utilisateur</th>
                <th class="px-4 py-3 text-left">Direction</th>
                <th class="px-4 py-3 text-left">Envoyé</th>
                <th class="px-4 py-3 text-left">Reçu</th>
                <th class="px-4 py-3 text-left">Statut</th>
                <th class="px-4 py-3 text-left">Compliance</th>
                <th class="px-4 py-3 text-left">Date</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($transactions as $tx)
            <tr class="hover:bg-gray-50/50 {{ $tx->compliance_status === 'flagged' ? 'bg-red-50/30' : '' }}">
                <td class="px-6 py-3 font-mono text-xs text-gray-500">{{ $tx->reference_number }}</td>
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-800">{{ $tx->user?->full_name }}</p>
                    <p class="text-xs text-gray-400">{{ $tx->user?->email }}</p>
                </td>
                <td class="px-4 py-3">
                    @if($tx->direction === 'africa_to_china')
                    <span class="text-xs font-medium text-orange-600 bg-orange-50 px-2 py-0.5 rounded-lg">🌍→🇨🇳</span>
                    @else
                    <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded-lg">🇨🇳→🌍</span>
                    @endif
                </td>
                <td class="px-4 py-3 font-semibold">{{ $tx->send_currency }} {{ number_format($tx->send_amount, 0, '.', ' ') }}</td>
                <td class="px-4 py-3 text-primary-600 font-semibold">¥{{ number_format($tx->receive_amount, 2, '.', ' ') }}</td>
                <td class="px-4 py-3">
                    @switch($tx->status)
                        @case('completed') <span class="badge-success">Complété</span> @break
                        @case('processing')
                        @case('payment_confirmed')
                        @case('sent_to_beneficiary') <span class="badge-info">En cours</span> @break
                        @case('failed') <span class="badge-danger">Échoué</span> @break
                        @case('on_hold') <span class="badge-warning">En attente</span> @break
                        @case('cancelled') <span class="badge-gray">Annulé</span> @break
                        @default <span class="badge-gray">{{ $tx->status }}</span>
                    @endswitch
                </td>
                <td class="px-4 py-3">
                    @switch($tx->compliance_status)
                        @case('flagged') <span class="badge-danger">Flagguée</span> @break
                        @case('cleared') <span class="badge-success">Validée</span> @break
                        @default <span class="badge-gray">—</span>
                    @endswitch
                </td>
                <td class="px-4 py-3 text-gray-400 text-xs">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.transactions.show', $tx->uuid) }}" class="text-primary-500 hover:underline text-xs">Voir</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-10 text-gray-400">Aucune transaction</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($transactions->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $transactions->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
