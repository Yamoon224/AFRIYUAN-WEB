@extends('layouts.app')

@section('title', 'Mes cartes')
@section('page-title', 'Mes cartes bancaires')

@section('content')
<div x-data="{
    showAdd: false,
    loading: false,
    stripe: null,
    cardElement: null,
    clientSecret: '{{ $setupIntentSecret ?? '' }}',
    error: null,

    async initStripe() {
        this.stripe = Stripe('{{ config('stripe.key') }}');
        const elements = this.stripe.elements();
        this.cardElement = elements.create('card', {
            style: {
                base: { fontFamily: 'Inter, sans-serif', fontSize: '15px', color: '#1f2937', '::placeholder': { color: '#9ca3af' } }
            },
            hidePostalCode: true
        });
        await this.$nextTick();
        this.cardElement.mount('#card-element');
    },

    async addCard() {
        this.loading = true;
        this.error = null;
        const { setupIntent, error } = await this.stripe.confirmCardSetup(this.clientSecret, {
            payment_method: { card: this.cardElement }
        });
        if (error) {
            this.error = error.message;
            this.loading = false;
            return;
        }
        document.getElementById('pm_id').value = setupIntent.payment_method;
        document.getElementById('card-store-form').submit();
    }
}" @open-add-card.window="showAdd = true; $nextTick(() => initStripe())">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-gray-500">{{ $cards->count() }} carte(s) enregistrée(s)</p>
        <button @click="showAdd = !showAdd; if(showAdd) $nextTick(() => initStripe())"
                class="btn-primary text-sm">
            + Ajouter une carte
        </button>
    </div>

    {{-- Add card panel --}}
    <div x-show="showAdd" x-cloak class="card mb-5">
        <h3 class="font-semibold text-gray-900 mb-4">Ajouter une carte Visa / Mastercard</h3>

        <div class="p-4 border-2 border-gray-200 rounded-xl mb-4" id="card-element">
            {{-- Stripe Card Element --}}
        </div>

        <div x-show="error" class="text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-4" x-text="error"></div>

        <form id="card-store-form" method="POST" action="{{ route('cards.store') }}">
            @csrf
            <input type="hidden" name="payment_method_id" id="pm_id">
        </form>

        <div class="flex gap-3">
            <button @click="showAdd = false" class="btn-ghost flex-1">Annuler</button>
            <button @click="addCard()" :disabled="loading" class="btn-primary flex-1">
                <span x-show="!loading">Enregistrer la carte</span>
                <span x-show="loading" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                    Traitement...
                </span>
            </button>
        </div>

        <p class="text-xs text-gray-400 mt-3 text-center">
            🔒 Paiements sécurisés par Stripe — vos données ne sont jamais stockées sur nos serveurs.
        </p>
    </div>

    {{-- Cards list --}}
    @if($cards->isEmpty())
    <div class="card text-center py-16">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
        </div>
        <p class="text-gray-700 font-medium mb-1">Aucune carte enregistrée</p>
        <p class="text-sm text-gray-400 mb-4">Ajoutez une carte Visa ou Mastercard pour effectuer vos transferts.</p>
        <button @click="showAdd = true; $nextTick(() => initStripe())" class="btn-primary text-sm">
            Ajouter une carte
        </button>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($cards as $card)
        <div class="card relative overflow-hidden">
            {{-- Card brand stripe --}}
            <div class="absolute top-0 left-0 right-0 h-1 {{ $card->card_brand === 'visa' ? 'bg-blue-500' : ($card->card_brand === 'mastercard' ? 'bg-orange-500' : 'bg-gray-400') }}"></div>

            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    {{-- Brand icon --}}
                    <div class="w-12 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                        @if($card->card_brand === 'visa')
                        <span class="text-blue-700 font-bold text-sm italic">VISA</span>
                        @elseif($card->card_brand === 'mastercard')
                        <div class="flex">
                            <div class="w-5 h-5 rounded-full bg-red-500 opacity-90"></div>
                            <div class="w-5 h-5 rounded-full bg-yellow-400 opacity-90 -ml-2.5"></div>
                        </div>
                        @else
                        <span class="text-gray-500 text-xs font-semibold uppercase">{{ $card->card_brand }}</span>
                        @endif
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">•••• {{ $card->last_four }}</p>
                        <p class="text-xs text-gray-400">Exp. {{ $card->exp_month }}/{{ $card->exp_year }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @if($card->is_default)
                    <span class="text-xs bg-green-100 text-green-700 font-medium px-2 py-0.5 rounded-lg">Par défaut</span>
                    @else
                    <form method="POST" action="{{ route('cards.setDefault', $card->id) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="text-xs text-gray-400 hover:text-primary-600 transition-colors">
                            Définir par défaut
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    @if($card->funding)
                    <span class="capitalize">{{ $card->funding }}</span>
                    @endif
                    @if($card->three_d_secure_usage)
                    <span class="text-green-500">3D Secure</span>
                    @endif
                </div>
                <form method="POST" action="{{ route('cards.destroy', $card->id) }}"
                      onsubmit="return confirm('Supprimer cette carte ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-gray-400 hover:text-red-500 transition-colors">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
@endpush
@endsection
