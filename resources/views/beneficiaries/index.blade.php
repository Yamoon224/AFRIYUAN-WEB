@extends('layouts.app')

@section('title', 'Bénéficiaires')
@section('page-title', 'Mes bénéficiaires')

@section('content')
<div x-data="{ deleteId: null, showConfirm: false }" @keydown.escape.window="showConfirm = false">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-gray-500">{{ $beneficiaries->total() }} bénéficiaire(s)</p>
        <a href="{{ route('beneficiaries.create') }}" class="btn-primary text-sm">
            + Ajouter un bénéficiaire
        </a>
    </div>

    @if($beneficiaries->isEmpty())
    <div class="card text-center py-16">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <p class="text-gray-700 font-medium mb-1">Aucun bénéficiaire</p>
        <p class="text-sm text-gray-400 mb-4">Ajoutez vos bénéficiaires habituels pour accélérer vos transferts.</p>
        <a href="{{ route('beneficiaries.create') }}" class="btn-primary text-sm">Ajouter un bénéficiaire</a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($beneficiaries as $ben)
        <div class="card hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl {{ $ben->beneficiary_type === 'china' ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600' }} flex items-center justify-center font-bold text-lg">
                        {{ strtoupper(substr($ben->first_name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $ben->first_name }} {{ $ben->last_name }}</p>
                        @if($ben->nickname)
                        <p class="text-xs text-gray-400">{{ $ben->nickname }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <a href="{{ route('beneficiaries.edit', $ben->id) }}" class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </a>
                    <button @click="deleteId = {{ $ben->id }}; showConfirm = true"
                            class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>

            <div class="mt-4 space-y-2">
                <div class="flex items-center gap-2">
                    @switch($ben->receive_method)
                        @case('bank_transfer')
                        <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-lg font-medium">Virement bancaire</span>
                        @break
                        @case('alipay')
                        <span class="text-xs bg-sky-50 text-sky-600 px-2 py-0.5 rounded-lg font-medium">Alipay</span>
                        @break
                        @case('wechat_pay')
                        <span class="text-xs bg-green-50 text-green-600 px-2 py-0.5 rounded-lg font-medium">WeChat Pay</span>
                        @break
                        @case('cash_pickup')
                        <span class="text-xs bg-amber-50 text-amber-600 px-2 py-0.5 rounded-lg font-medium">Retrait espèces</span>
                        @break
                    @endswitch
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-lg">
                        {{ $ben->beneficiary_type === 'china' ? '🇨🇳 Chine' : '🌍 Afrique' }}
                    </span>
                </div>

                @if($ben->receive_method === 'bank_transfer')
                <p class="text-xs text-gray-500">{{ $ben->bank_name }} — {{ $ben->bank_account_number }}</p>
                @elseif(in_array($ben->receive_method, ['alipay', 'wechat_pay']))
                <p class="text-xs text-gray-500">{{ $ben->wallet_account_number }}</p>
                @endif

                @if($ben->phone_number)
                <p class="text-xs text-gray-400">{{ $ben->phone_number }}</p>
                @endif
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="{{ route('transfers.create', ['beneficiary' => $ben->id]) }}"
                   class="btn-secondary w-full text-xs text-center block">
                    Envoyer de l'argent
                </a>
            </div>
        </div>
        @endforeach
    </div>

    @if($beneficiaries->hasPages())
    <div class="mt-5">{{ $beneficiaries->links() }}</div>
    @endif
    @endif

    {{-- Delete confirm modal --}}
    <div x-show="showConfirm" x-cloak
         class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4"
         @click.self="showConfirm = false">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-xl">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Supprimer ce bénéficiaire ?</h3>
            <p class="text-sm text-gray-500 mb-5">Cette action est irréversible. Les transferts passés ne seront pas affectés.</p>
            <div class="flex gap-3">
                <button @click="showConfirm = false" class="btn-ghost flex-1">Annuler</button>
                <form :action="`/beneficiaries/${deleteId}`" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold text-sm px-4 py-2.5 rounded-xl transition-colors">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
