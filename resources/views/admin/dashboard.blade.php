@extends('layouts.admin')

@section('title', 'Tableau de bord Admin')
@section('page-title', 'Tableau de bord')

@section('content')

{{-- Stats row --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Transactions aujourd'hui</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['today_count'] }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ number_format($stats['today_volume'], 0, '.', ' ') }} XOF</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Ce mois</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['month_count'] }}</p>
        <p class="text-xs text-primary-500 mt-1">¥{{ number_format($stats['month_volume_cny'], 2, '.', ' ') }} CNY</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Utilisateurs actifs</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['active_users'] }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $stats['new_users_today'] }} nouveaux aujourd'hui</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">KYC en attente</p>
        <p class="text-3xl font-bold {{ $stats['kyc_pending'] > 0 ? 'text-amber-500' : 'text-gray-900' }} mt-1">
            {{ $stats['kyc_pending'] }}
        </p>
        <a href="{{ route('admin.kyc.index') }}" class="text-xs text-primary-500 mt-1 hover:underline">Voir les dossiers →</a>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- Recent transactions --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Dernières transactions</h3>
            <a href="{{ route('admin.transactions.index') }}" class="text-xs text-primary-500 hover:underline">Tout voir</a>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">Référence</th>
                    <th class="px-4 py-3 text-left">Utilisateur</th>
                    <th class="px-4 py-3 text-left">Montant</th>
                    <th class="px-4 py-3 text-left">Statut</th>
                    <th class="px-4 py-3 text-left">Heure</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($recentTransactions as $tx)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-6 py-3 font-mono text-xs text-gray-500">{{ $tx->reference_number }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $tx->user?->full_name }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $tx->send_currency }} {{ number_format($tx->send_amount, 0, '.', ' ') }}</td>
                    <td class="px-4 py-3">
                        @switch($tx->status)
                            @case('completed') <span class="badge-success">Complété</span> @break
                            @case('processing') <span class="badge-info">En cours</span> @break
                            @case('failed') <span class="badge-danger">Échoué</span> @break
                            @default <span class="badge-gray">{{ $tx->status }}</span>
                        @endswitch
                    </td>
                    <td class="px-4 py-3 text-gray-400 text-xs">{{ $tx->created_at->format('H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Flags & alerts --}}
    <div class="space-y-4">
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h3 class="font-semibold text-gray-900 mb-3">Alertes compliance</h3>
            @if($flaggedCount > 0)
            <div class="flex items-center gap-3 p-3 bg-red-50 rounded-xl mb-3">
                <span class="text-2xl font-bold text-red-500">{{ $flaggedCount }}</span>
                <div>
                    <p class="text-sm font-medium text-red-700">Transactions flagguées</p>
                    <a href="{{ route('admin.transactions.index', ['compliance' => 'flagged']) }}" class="text-xs text-red-500 hover:underline">Examiner →</a>
                </div>
            </div>
            @else
            <p class="text-sm text-gray-400">Aucune alerte en cours</p>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h3 class="font-semibold text-gray-900 mb-3">Répartition des directions</h3>
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">🌍 → 🇨🇳 Afrique→Chine</span>
                    <span class="font-semibold text-sm">{{ $stats['africa_to_china'] }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-primary-500 h-2 rounded-full" style="width: {{ $stats['africa_to_china'] }}%"></div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">🇨🇳 → 🌍 Chine→Afrique</span>
                    <span class="font-semibold text-sm">{{ $stats['china_to_africa'] }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $stats['china_to_africa'] }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
