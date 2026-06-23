@extends('layouts.app')

@section('title', isset($beneficiary) ? 'Modifier le bénéficiaire' : 'Nouveau bénéficiaire')
@section('page-title', isset($beneficiary) ? 'Modifier le bénéficiaire' : 'Nouveau bénéficiaire')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('beneficiaries.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux bénéficiaires
    </a>

    <div class="card" x-data="{
        type: '{{ old('beneficiary_type', $beneficiary->beneficiary_type ?? 'china') }}',
        method: '{{ old('receive_method', $beneficiary->receive_method ?? 'bank_transfer') }}'
    }">
        <h2 class="text-lg font-semibold text-gray-900 mb-5">
            {{ isset($beneficiary) ? 'Modifier les informations' : 'Ajouter un bénéficiaire' }}
        </h2>

        @if($errors->any())
        <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
            <ul class="space-y-1 list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST"
              action="{{ isset($beneficiary) ? route('beneficiaries.update', $beneficiary->id) : route('beneficiaries.store') }}"
              class="space-y-5">
            @csrf
            @if(isset($beneficiary)) @method('PUT') @endif

            {{-- Type --}}
            <div>
                <p class="text-sm font-medium text-gray-700 mb-2">Destination du bénéficiaire</p>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="beneficiary_type" value="china" x-model="type" class="sr-only">
                        <div :class="type === 'china' ? 'border-primary-500 bg-primary-50 ring-2 ring-primary-200' : 'border-gray-200'"
                             class="border-2 rounded-xl p-4 text-center transition-all">
                            <span class="text-2xl">🇨🇳</span>
                            <p class="text-sm font-medium mt-1 text-gray-700">Chine</p>
                            <p class="text-xs text-gray-400">CNY · Alipay / WeChat / Virement</p>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="beneficiary_type" value="africa" x-model="type" class="sr-only">
                        <div :class="type === 'africa' ? 'border-primary-500 bg-primary-50 ring-2 ring-primary-200' : 'border-gray-200'"
                             class="border-2 rounded-xl p-4 text-center transition-all">
                            <span class="text-2xl">🌍</span>
                            <p class="text-sm font-medium mt-1 text-gray-700">Afrique</p>
                            <p class="text-xs text-gray-400">XOF / XAF / GHS / GNF…</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Prénom / Nom --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="fl-group">
                    <input type="text" name="first_name" placeholder=" " required
                           value="{{ old('first_name', $beneficiary->first_name ?? '') }}"
                           class="fl-input {{ $errors->has('first_name') ? 'error' : '' }}">
                    <label class="fl-label">Prénom</label>
                </div>
                <div class="fl-group">
                    <input type="text" name="last_name" placeholder=" " required
                           value="{{ old('last_name', $beneficiary->last_name ?? '') }}"
                           class="fl-input {{ $errors->has('last_name') ? 'error' : '' }}">
                    <label class="fl-label">Nom de famille</label>
                </div>
            </div>

            <div class="fl-group">
                <input type="text" name="nickname" placeholder=" "
                       value="{{ old('nickname', $beneficiary->nickname ?? '') }}"
                       class="fl-input">
                <label class="fl-label">Surnom (optionnel)</label>
            </div>

            <div class="fl-group">
                <input type="tel" name="phone_number" placeholder=" "
                       value="{{ old('phone_number', $beneficiary->phone_number ?? '') }}"
                       class="fl-input {{ $errors->has('phone_number') ? 'error' : '' }}">
                <label class="fl-label">Numéro de téléphone</label>
            </div>

            {{-- Mode de réception --}}
            <div>
                <p class="text-sm font-medium text-gray-700 mb-2">Mode de réception</p>
                <div class="grid grid-cols-2 gap-2">
                    <template x-if="type === 'china'">
                        <div class="contents">
                            @foreach(['bank_transfer' => 'Virement bancaire', 'alipay' => 'Alipay', 'wechat_pay' => 'WeChat Pay', 'cash_pickup' => 'Retrait espèces'] as $val => $lbl)
                            <label class="cursor-pointer">
                                <input type="radio" name="receive_method" value="{{ $val }}" x-model="method" class="sr-only">
                                <div :class="method === '{{ $val }}' ? 'border-primary-500 bg-primary-50' : 'border-gray-200'"
                                     class="border-2 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 text-center transition-all">
                                    {{ $lbl }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </template>
                    <template x-if="type === 'africa'">
                        <div class="contents">
                            @foreach(['bank_transfer' => 'Virement bancaire', 'cash_pickup' => 'Retrait espèces'] as $val => $lbl)
                            <label class="cursor-pointer">
                                <input type="radio" name="receive_method" value="{{ $val }}" x-model="method" class="sr-only">
                                <div :class="method === '{{ $val }}' ? 'border-primary-500 bg-primary-50' : 'border-gray-200'"
                                     class="border-2 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-700 text-center transition-all">
                                    {{ $lbl }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </template>
                </div>
            </div>

            {{-- Coordonnées bancaires --}}
            <div x-show="method === 'bank_transfer'" class="space-y-4 pt-1">
                <div class="fl-group">
                    <input type="text" name="bank_name" placeholder=" "
                           value="{{ old('bank_name', $beneficiary->bank_name ?? '') }}"
                           class="fl-input">
                    <label class="fl-label">Nom de la banque</label>
                </div>
                <div class="fl-group">
                    <input type="text" name="bank_account_number" placeholder=" "
                           value="{{ old('bank_account_number', $beneficiary->bank_account_number ?? '') }}"
                           class="fl-input">
                    <label class="fl-label">Numéro de compte</label>
                </div>
                <div class="fl-group">
                    <input type="text" name="bank_routing_number" placeholder=" "
                           value="{{ old('bank_routing_number', $beneficiary->bank_routing_number ?? '') }}"
                           class="fl-input">
                    <label class="fl-label">Code SWIFT / Routage (optionnel)</label>
                </div>
            </div>

            {{-- Portefeuille numérique --}}
            <div x-show="method === 'alipay' || method === 'wechat_pay'" class="pt-1">
                <div class="fl-group">
                    <input type="text" name="wallet_account_number" placeholder=" "
                           value="{{ old('wallet_account_number', $beneficiary->wallet_account_number ?? '') }}"
                           class="fl-input">
                    <label class="fl-label">
                        Identifiant <span x-text="method === 'alipay' ? 'Alipay' : 'WeChat'"></span>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-primary w-full">
                {{ isset($beneficiary) ? 'Enregistrer les modifications' : 'Ajouter le bénéficiaire' }}
            </button>
        </form>
    </div>
</div>
@endsection
