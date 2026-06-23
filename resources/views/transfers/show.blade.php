@extends('layouts.app')

@section('title', 'Détail du transfert')
@section('page-title', 'Détail du transfert')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Back + Actions --}}
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('transfers.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Retour à l'historique
        </a>
        @if($transaction->canBeCancelled())
        <form method="POST" action="{{ route('transfers.cancel', $transaction->uuid) }}"
              onsubmit="return confirm('Confirmer l\'annulation de ce transfert ?')">
            @csrf @method('PATCH')
            <button type="submit" class="btn-ghost text-sm text-red-600 hover:bg-red-50">
                Annuler le transfert
            </button>
        </form>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Main card --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Status card --}}
            <div class="card">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-mono mb-1">{{ $transaction->reference_number }}</p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $transaction->send_currency }} {{ number_format($transaction->send_amount, 0, '.', ' ') }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            → <span class="text-primary-600 font-semibold">
                            ¥{{ number_format($transaction->receive_amount, 2, '.', ' ') }} CNY
                            </span>
                        </p>
                    </div>
                    <div class="text-right">
                        @switch($transaction->status)
                            @case('completed')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-semibold bg-green-100 text-green-700">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span> Complété
                            </span>
                            @break
                            @case('processing')
                            @case('payment_confirmed')
                            @case('sent_to_beneficiary')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-semibold bg-blue-100 text-blue-700">
                                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> En cours
                            </span>
                            @break
                            @case('failed')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-semibold bg-red-100 text-red-700">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span> Échoué
                            </span>
                            @break
                            @case('cancelled')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-semibold bg-gray-100 text-gray-600">
                                <span class="w-2 h-2 rounded-full bg-gray-400"></span> Annulé
                            </span>
                            @break
                            @case('refunded')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-semibold bg-amber-100 text-amber-700">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span> Remboursé
                            </span>
                            @break
                        @endswitch
                        <p class="text-xs text-gray-400 mt-2">{{ $transaction->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>

                {{-- Direction + Progress --}}
                <div class="mt-5 pt-5 border-t border-gray-100">
                    <div class="flex items-center gap-3 mb-4">
                        @if($transaction->direction === 'africa_to_china')
                        <span class="text-sm font-medium">🌍 Afrique</span>
                        <div class="flex-1 h-px bg-gray-200 relative">
                            <div class="h-px bg-primary-400 {{ $transaction->status === 'completed' ? 'w-full' : 'w-1/2' }} transition-all duration-500"></div>
                        </div>
                        <span class="text-sm font-medium">🇨🇳 Chine</span>
                        @else
                        <span class="text-sm font-medium">🇨🇳 Chine</span>
                        <div class="flex-1 h-px bg-gray-200 relative">
                            <div class="h-px bg-blue-400 {{ $transaction->status === 'completed' ? 'w-full' : 'w-1/2' }} transition-all duration-500"></div>
                        </div>
                        <span class="text-sm font-medium">🌍 Afrique</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Transfer details --}}
            <div class="card">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Détails du transfert</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <dt class="text-gray-500">Montant envoyé</dt>
                        <dd class="font-medium">{{ $transaction->send_currency }} {{ number_format($transaction->send_amount, 0, '.', ' ') }}</dd>
                    </div>
                    <div class="flex justify-between text-sm">
                        <dt class="text-gray-500">Frais de transfert</dt>
                        <dd class="font-medium">{{ $transaction->send_currency }} {{ number_format($transaction->fee_amount, 0, '.', ' ') }}</dd>
                    </div>
                    <div class="flex justify-between text-sm">
                        <dt class="text-gray-500">Taux de change</dt>
                        <dd class="font-medium">1 {{ $transaction->send_currency }} = ¥{{ $transaction->exchange_rate }} CNY</dd>
                    </div>
                    <div class="border-t border-gray-100 pt-3 flex justify-between text-sm font-semibold">
                        <dt class="text-gray-700">Montant reçu</dt>
                        <dd class="text-primary-600">¥{{ number_format($transaction->receive_amount, 2, '.', ' ') }} CNY</dd>
                    </div>
                    <div class="flex justify-between text-sm">
                        <dt class="text-gray-500">Méthode de paiement</dt>
                        <dd class="font-medium capitalize">{{ str_replace('_', ' ', $transaction->payment_method) }}</dd>
                    </div>
                    @if($transaction->stripe_payment_intent_id)
                    <div class="flex justify-between text-sm">
                        <dt class="text-gray-500">ID Stripe</dt>
                        <dd class="font-mono text-xs text-gray-400">{{ $transaction->stripe_payment_intent_id }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Status history --}}
            @if($transaction->statusLogs->count())
            <div class="card">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Historique des statuts</h3>
                <div class="space-y-3">
                    @foreach($transaction->statusLogs->sortByDesc('created_at') as $log)
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 rounded-full bg-primary-400 mt-1.5 shrink-0"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $log->new_status) }}</p>
                            @if($log->note)
                            <p class="text-xs text-gray-400">{{ $log->note }}</p>
                            @endif
                            <p class="text-xs text-gray-300">{{ $log->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            {{-- Beneficiary --}}
            @if($transaction->beneficiary)
            <div class="card">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Bénéficiaire</h3>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center text-primary-600 font-bold text-sm">
                        {{ strtoupper(substr($transaction->beneficiary->first_name, 0, 1)) }}{{ strtoupper(substr($transaction->beneficiary->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $transaction->beneficiary->first_name }} {{ $transaction->beneficiary->last_name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $transaction->beneficiary->receive_method) }}</p>
                    </div>
                </div>
                @if($transaction->beneficiary->receive_method === 'bank_transfer')
                <div class="mt-3 pt-3 border-t border-gray-100 space-y-1 text-xs text-gray-500">
                    <p>{{ $transaction->beneficiary->bank_name }}</p>
                    <p class="font-mono">{{ $transaction->beneficiary->bank_account_number }}</p>
                </div>
                @elseif(in_array($transaction->beneficiary->receive_method, ['alipay', 'wechat_pay']))
                <div class="mt-3 pt-3 border-t border-gray-100 text-xs text-gray-500">
                    <p>{{ $transaction->beneficiary->wallet_account_number }}</p>
                </div>
                @endif
            </div>
            @endif

            {{-- Receipt PDF --}}
            <div class="card text-center">
                <svg class="w-8 h-8 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm text-gray-500 mb-3">Reçu de transfert</p>
                <button disabled class="btn-secondary w-full text-sm opacity-50">
                    Télécharger PDF
                </button>
                <p class="text-xs text-gray-400 mt-1">Disponible après complétion</p>
            </div>

            {{-- Help --}}
            <div class="card bg-gray-50">
                <p class="text-sm font-medium text-gray-700 mb-2">Un problème ?</p>
                <p class="text-xs text-gray-500 mb-3">Notre équipe support est disponible 7j/7.</p>
                <a href="{{ route('support.create') }}" class="btn-secondary w-full text-sm">Contacter le support</a>
            </div>
        </div>
    </div>
</div>
@endsection
