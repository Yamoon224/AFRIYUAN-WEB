@extends('layouts.admin')

@section('title', 'Taux de change')
@section('page-title', 'Gestion des taux de change')

@section('content')

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- Rates table --}}
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h3 class="font-semibold text-gray-900">Taux actuels</h3>
                <p class="text-xs text-gray-400 mt-0.5">
                    Définis manuellement · Dernière modification :
                    {{ $lastUpdated ? $lastUpdated->diffForHumans() : 'jamais' }}
                </p>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-400 bg-gray-50 border border-gray-200 px-3 py-2 rounded-xl">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Taux gérés en backoffice uniquement
            </div>
        </div>

        @if($rates->isEmpty())
        <div class="text-center py-12">
            <p class="text-gray-400 text-sm">Aucun taux configuré. Utilisez le formulaire ci-contre.</p>
        </div>
        @else
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">Paire</th>
                    <th class="px-4 py-3 text-left">Taux marché</th>
                    <th class="px-4 py-3 text-left">Taux client</th>
                    <th class="px-4 py-3 text-left">Marge</th>
                    <th class="px-4 py-3 text-left">Modifié</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($rates as $rate)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-6 py-3 font-semibold text-gray-900">
                        {{ $rate->from_currency }} → {{ $rate->to_currency }}
                    </td>
                    <td class="px-4 py-3 font-mono text-gray-600">{{ number_format($rate->rate, 6) }}</td>
                    <td class="px-4 py-3 font-mono font-semibold text-primary-600">{{ number_format($rate->margin_rate, 6) }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $rate->margin_percent }}%</td>
                    <td class="px-4 py-3 text-xs text-gray-400">{{ $rate->fetched_at?->diffForHumans() ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.exchange-rates.destroy', $rate->id) }}"
                              onsubmit="return confirm('Supprimer ce taux ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-gray-400 hover:text-red-500 transition-colors">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- Manual entry form --}}
    <div class="space-y-4">
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h3 class="font-semibold text-gray-900 mb-1">Configurer un taux</h3>
            <p class="text-xs text-gray-400 mb-4">
                Saisissez le taux de marché et la marge commerciale. Le taux appliqué aux clients sera calculé automatiquement.
            </p>

            @if($errors->any())
            <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.exchange-rates.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Devise source</label>
                    <select name="from_currency" required class="input-field text-sm">
                        @foreach(['XOF','XAF','GNF','GHS','LRD','SLE','CNY'] as $c)
                        <option value="{{ $c }}" {{ old('from_currency') === $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Devise cible</label>
                    <select name="to_currency" required class="input-field text-sm">
                        @foreach(['CNY','XOF','XAF','GNF','GHS','LRD','SLE'] as $c)
                        <option value="{{ $c }}" {{ old('to_currency') === $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Taux de marché</label>
                    <input type="number" name="rate" step="0.00000001" required
                           value="{{ old('rate') }}"
                           class="input-field text-sm" placeholder="0.01183500">
                    <p class="text-xs text-gray-400 mt-1">Ex: 1 XOF = 0.01183 CNY</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Marge commerciale (%)</label>
                    <input type="number" name="margin_percent" step="0.01" min="0" max="20"
                           value="{{ old('margin_percent', '2.50') }}" class="input-field text-sm">
                    <p class="text-xs text-gray-400 mt-1">Taux client = taux marché × (1 − marge)</p>
                </div>
                <button type="submit" class="btn-primary w-full text-sm">Enregistrer le taux</button>
            </form>
        </div>

        {{-- Transfer fees info --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-900">Frais par corridor</h3>
            </div>
            <table class="w-full text-xs">
                <thead class="bg-gray-50 text-gray-400 uppercase">
                    <tr>
                        <th class="px-4 py-2 text-left">Corridor</th>
                        <th class="px-3 py-2 text-left">Type</th>
                        <th class="px-3 py-2 text-left">%</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($fees as $fee)
                    <tr>
                        <td class="px-4 py-2 font-medium text-gray-700">{{ $fee->from_currency }}→{{ $fee->to_currency }}</td>
                        <td class="px-3 py-2 text-gray-500 capitalize">{{ $fee->fee_type }}</td>
                        <td class="px-3 py-2 text-gray-500">{{ $fee->percentage_fee ? $fee->percentage_fee.'%' : '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
