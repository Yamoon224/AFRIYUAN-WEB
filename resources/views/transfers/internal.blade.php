@extends('layouts.app')

@section('title', __('transfer.internal'))
@section('page-title', __('transfer.internal'))
@section('breadcrumb', __('nav.wallet') . ' / ' . __('transfer.internal'))

@section('content')
<div class="max-w-lg mx-auto">
    <div class="card" x-data="internalTransfer()">

        {{-- Balance info --}}
        <div class="mb-6 p-4 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('wallet.balance') }}</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white">
                    {{ number_format((float) $wallet->balance, 2, ',', ' ') }}
                    <span class="text-sm font-semibold text-gray-500">{{ $wallet->currency_code }}</span>
                </p>
            </div>
            <a href="{{ route('wallet.index') }}" class="btn-ghost text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                {{ __('general.back') }}
            </a>
        </div>

        <form method="POST" action="{{ route('internal-transfers.store') }}" class="space-y-5" @submit.prevent="submit">
            @csrf
            <input type="hidden" name="receiver_uuid" x-model="receiverUuid">

            {{-- Search recipient --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('transfer.recipient') }}
                </label>
                <div class="relative">
                    <input type="text"
                           x-model="query"
                           @input.debounce.400ms="search"
                           placeholder="{{ __('transfer.search_recipient') }}"
                           class="input-field pr-10"
                           autocomplete="off">
                    <svg x-show="loading" class="absolute right-3 top-3.5 w-4 h-4 text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                </div>

                {{-- Results dropdown --}}
                <div x-show="results.length > 0 && !selected"
                     class="mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg overflow-hidden">
                    <template x-for="user in results" :key="user.uuid">
                        <button type="button"
                                @click="selectUser(user)"
                                class="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 text-left transition-colors">
                            <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center shrink-0">
                                <span class="text-xs font-bold text-primary-700 dark:text-primary-300"
                                      x-text="(user.first_name[0] + user.last_name[0]).toUpperCase()"></span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white" x-text="user.first_name + ' ' + user.last_name"></p>
                                <p class="text-xs text-gray-400 truncate" x-text="user.email"></p>
                            </div>
                        </button>
                    </template>
                </div>

                {{-- Selected recipient --}}
                <div x-show="selected" class="mt-2 flex items-center justify-between p-3 bg-primary-50 dark:bg-primary-950 border border-primary-200 dark:border-primary-800 rounded-xl">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-primary-500 flex items-center justify-center shrink-0">
                            <span class="text-xs font-bold text-white"
                                  x-text="selected ? (selected.first_name[0] + selected.last_name[0]).toUpperCase() : ''"></span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white"
                               x-text="selected ? selected.first_name + ' ' + selected.last_name : ''"></p>
                            <p class="text-xs text-gray-400" x-text="selected ? selected.email : ''"></p>
                        </div>
                    </div>
                    <button type="button" @click="clearRecipient()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Amount --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('transfer.amount') }} ({{ $wallet->currency_code }})
                </label>
                <input type="number" name="amount" x-model="amount" min="1" step="0.01"
                       max="{{ $wallet->balance }}" required class="input-field" placeholder="0.00">
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    {{ __('transfer.description') }}
                </label>
                <input type="text" name="description" class="input-field" placeholder="{{ __('transfer.description') }}">
            </div>

            {{-- Submit --}}
            <button type="submit"
                    :disabled="!selected || !amount || submitting"
                    class="btn-primary w-full"
                    :class="{ 'opacity-50 cursor-not-allowed': !selected || !amount || submitting }">
                <svg x-show="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                {{ __('transfer.confirm') }}
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function internalTransfer() {
    return {
        query: '',
        results: [],
        selected: null,
        receiverUuid: '',
        amount: '',
        loading: false,
        submitting: false,

        async search() {
            if (this.query.length < 3) { this.results = []; return; }
            this.loading = true;
            try {
                const res = await fetch(`{{ route('internal-transfers.search') }}?q=` + encodeURIComponent(this.query), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await res.json();
                this.results = data;
            } finally {
                this.loading = false;
            }
        },

        selectUser(user) {
            this.selected = user;
            this.receiverUuid = user.uuid;
            this.results = [];
        },

        clearRecipient() {
            this.selected = null;
            this.receiverUuid = '';
            this.query = '';
        },

        submit(e) {
            if (!this.selected || !this.amount) return;
            this.submitting = true;
            e.target.submit();
        },
    };
}
</script>
@endpush
