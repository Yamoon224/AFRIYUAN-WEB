@extends('layouts.app')

@section('title', __('wallet.title'))
@section('page-title', __('wallet.title'))
@section('breadcrumb', __('nav.account') . ' / ' . __('wallet.title'))

@section('content')
<div class="space-y-6">

    {{-- Balance card --}}
    <div class="rounded-2xl p-6 text-white shadow-lg"
         style="background: linear-gradient(135deg, #D4132B 0%, #AA0E22 50%, #7f0a18 100%);">
        <p class="text-sm font-medium text-red-200 mb-1">{{ __('wallet.balance') }}</p>
        <p class="text-4xl font-black tracking-tight">
            {{ number_format((float) $wallet->balance, 2, ',', ' ') }}
            <span class="text-xl font-semibold">{{ $wallet->currency_code }}</span>
        </p>
        <div class="mt-3 flex items-center gap-2">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium
                         {{ $wallet->status === 'active' ? 'bg-green-400/20 text-green-200' : 'bg-gray-400/20 text-gray-200' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $wallet->status === 'active' ? 'bg-green-400' : 'bg-gray-400' }}"></span>
                {{ __('wallet.status_' . $wallet->status) }}
            </span>
        </div>

        <div class="mt-5 flex gap-3">
            {{-- Top-up button --}}
            <button onclick="document.getElementById('modal-topup').classList.remove('hidden')"
                    class="flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur rounded-xl text-sm font-semibold transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('wallet.topup') }}
            </button>

            {{-- Withdraw button --}}
            @if($wallet->isActive())
            <button onclick="document.getElementById('modal-withdraw').classList.remove('hidden')"
                    class="flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur rounded-xl text-sm font-semibold transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                {{ __('wallet.withdraw') }}
            </button>
            @endif

            {{-- Send internal --}}
            <a href="{{ route('internal-transfers.create') }}"
               class="flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur rounded-xl text-sm font-semibold transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                {{ __('wallet.send_internal') }}
            </a>
        </div>
    </div>

    {{-- Transactions --}}
    <div class="card">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">{{ __('wallet.transactions') }}</h3>

        @if($transactions->isEmpty())
            <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-8">{{ __('wallet.no_transactions') }}</p>
        @else
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($transactions as $tx)
                <div class="flex items-center justify-between py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center
                                    {{ $tx->type === 'credit' ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }}">
                            @if($tx->type === 'credit')
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            @else
                                <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $tx->description }}</p>
                            <p class="text-xs text-gray-400">{{ $tx->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold {{ $tx->type === 'credit' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $tx->type === 'credit' ? '+' : '-' }}{{ number_format((float) $tx->amount, 2, ',', ' ') }}
                        {{ $wallet->currency_code }}
                    </span>
                </div>
                @endforeach
            </div>

            <div class="mt-4">{{ $transactions->links() }}</div>
        @endif
    </div>
</div>

{{-- Modal Top-up --}}
<div id="modal-topup" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
     x-data @click.self="$el.classList.add('hidden')">
    <div class="card w-full max-w-md">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">{{ __('wallet.confirm_topup') }}</h3>
        <form method="POST" action="{{ route('wallet.topup') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('wallet.topup_amount') }}</label>
                <input type="number" name="amount" min="1" step="0.01" required
                       class="input-field" placeholder="0.00">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('wallet.description') }} ({{ __('general.optional') }})</label>
                <input type="text" name="description" class="input-field" placeholder="{{ __('wallet.description') }}">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modal-topup').classList.add('hidden')"
                        class="btn-secondary flex-1">{{ __('general.cancel') }}</button>
                <button type="submit" class="btn-primary flex-1">{{ __('wallet.topup') }}</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Withdraw --}}
<div id="modal-withdraw" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
     x-data @click.self="$el.classList.add('hidden')">
    <div class="card w-full max-w-md">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">{{ __('wallet.confirm_withdraw') }}</h3>
        <form method="POST" action="{{ route('wallet.withdraw') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('wallet.withdraw_amount') }}</label>
                <input type="number" name="amount" min="1" step="0.01" max="{{ $wallet->balance }}" required
                       class="input-field" placeholder="0.00">
                <p class="text-xs text-gray-400 mt-1">{{ __('wallet.balance') }} : {{ number_format((float)$wallet->balance, 2, ',', ' ') }} {{ $wallet->currency_code }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('wallet.description') }} ({{ __('general.optional') }})</label>
                <input type="text" name="description" class="input-field">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modal-withdraw').classList.add('hidden')"
                        class="btn-secondary flex-1">{{ __('general.cancel') }}</button>
                <button type="submit" class="btn-primary flex-1">{{ __('wallet.withdraw') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
