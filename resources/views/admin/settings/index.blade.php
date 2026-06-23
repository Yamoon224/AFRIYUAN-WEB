@extends('layouts.admin')

@section('title', 'Paramètres')
@section('page-title', 'Paramètres de l\'application')

@section('content')
<div class="max-w-2xl space-y-5">
    @if(session('settings_success'))
    <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
        {{ session('settings_success') }}
    </div>
    @endif

    {{-- General --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="font-semibold text-gray-900 mb-4">Paramètres généraux</h3>
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
            @csrf @method('PATCH')

            @foreach([
                ['key' => 'app_maintenance_mode', 'label' => 'Mode maintenance', 'type' => 'toggle'],
                ['key' => 'transfers_enabled', 'label' => 'Transferts activés', 'type' => 'toggle'],
                ['key' => 'africa_to_china_enabled', 'label' => 'Direction Afrique→Chine', 'type' => 'toggle'],
                ['key' => 'china_to_africa_enabled', 'label' => 'Direction Chine→Afrique', 'type' => 'toggle'],
                ['key' => 'kyc_required', 'label' => 'KYC obligatoire', 'type' => 'toggle'],
                ['key' => 'support_email', 'label' => 'Email support', 'type' => 'email'],
                ['key' => 'max_transfer_per_day', 'label' => 'Transfert max/jour (XOF)', 'type' => 'number'],
            ] as $setting)
            <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                <label class="text-sm font-medium text-gray-700">{{ $setting['label'] }}</label>
                @if($setting['type'] === 'toggle')
                <div x-data="{ on: {{ ($settings[$setting['key']] ?? false) ? 'true' : 'false' }} }" class="flex items-center gap-2">
                    <input type="hidden" name="{{ $setting['key'] }}" :value="on ? '1' : '0'">
                    <button type="button" @click="on = !on"
                            :class="on ? 'bg-primary-500' : 'bg-gray-200'"
                            class="w-10 h-6 rounded-full transition-colors relative">
                        <span :class="on ? 'translate-x-4' : 'translate-x-0'"
                              class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white shadow-sm transition-transform"></span>
                    </button>
                </div>
                @else
                <input type="{{ $setting['type'] }}" name="{{ $setting['key'] }}"
                       value="{{ $settings[$setting['key']] ?? '' }}"
                       class="text-sm border border-gray-200 rounded-xl px-3 py-2 w-48 focus:ring-2 focus:ring-primary-500/30">
                @endif
            </div>
            @endforeach

            <button type="submit" class="btn-primary">Enregistrer</button>
        </form>
    </div>

    {{-- Transfer fees --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Frais de transfert</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">Corridor</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Fixe</th>
                    <th class="px-4 py-3 text-left">%</th>
                    <th class="px-4 py-3 text-left">Min / Max</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($fees as $fee)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $fee->from_currency }} → {{ $fee->to_currency }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-lg capitalize">{{ $fee->fee_type }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $fee->fixed_fee ? number_format($fee->fixed_fee, 0) : '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $fee->percentage_fee ? $fee->percentage_fee . '%' : '—' }}</td>
                    <td class="px-4 py-3 text-xs text-gray-400">
                        {{ $fee->min_fee ? number_format($fee->min_fee, 0) : '—' }} /
                        {{ $fee->max_fee ? number_format($fee->max_fee, 0) : '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
