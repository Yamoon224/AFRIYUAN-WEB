@extends('layouts.app')

@section('title', 'Envoyer de l\'argent')
@section('page-title', 'Nouveau transfert')
@section('breadcrumb', 'Afrique ↔ Chine · Bidirectionnel')

@section('content')

<div class="max-w-2xl mx-auto" x-data="transferWizard()">

    {{-- Step indicator --}}
    <div class="flex items-center justify-between mb-8">
        @foreach([['Montant','1'],['Bénéficiaire','2'],['Paiement','3'],['Confirmation','4']] as $step)
        <div class="flex items-center {{ !$loop->last ? 'flex-1' : '' }}">
            <div class="flex items-center gap-2 shrink-0">
                <div :class="{
                    'bg-primary-500 text-white': currentStep >= {{ $step[1] }},
                    'bg-gray-100 text-gray-400': currentStep < {{ $step[1] }}
                }" class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-colors duration-200">
                    <template x-if="currentStep > {{ $step[1] }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </template>
                    <template x-if="currentStep <= {{ $step[1] }}">
                        <span>{{ $step[1] }}</span>
                    </template>
                </div>
                <span :class="currentStep >= {{ $step[1] }} ? 'text-gray-900' : 'text-gray-400'"
                      class="text-sm font-medium hidden sm:block">{{ $step[0] }}</span>
            </div>
            @if(!$loop->last)
            <div :class="currentStep > {{ $step[1] }} ? 'bg-primary-500' : 'bg-gray-200'"
                 class="flex-1 h-0.5 mx-3 transition-colors duration-200"></div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- STEP 1: Amount --}}
    <div x-show="currentStep === 1" x-transition class="card space-y-5">
        <h2 class="text-lg font-bold text-gray-900">Montant et devise</h2>

        {{-- Direction toggle --}}
        <div class="flex gap-2">
            <button @click="direction='africa_to_china'; updateCurrencies()"
                    :class="direction === 'africa_to_china' ? 'bg-primary-500 text-white border-primary-500' : 'bg-white text-gray-600 border-gray-200'"
                    class="flex-1 py-2.5 rounded-xl border font-medium text-sm transition-all">
                🌍 Afrique → Chine 🇨🇳
            </button>
            <button @click="direction='china_to_africa'; updateCurrencies()"
                    :class="direction === 'china_to_africa' ? 'bg-primary-500 text-white border-primary-500' : 'bg-white text-gray-600 border-gray-200'"
                    class="flex-1 py-2.5 rounded-xl border font-medium text-sm transition-all">
                🇨🇳 Chine → Afrique 🌍
            </button>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Je paye en</label>
                <select x-model="fromCurrency" @change="getQuote()" class="input-field">
                    <template x-for="c in fromCurrencies" :key="c.code">
                        <option :value="c.code" x-text="c.code + ' — ' + c.name"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Destinataire reçoit en</label>
                <select x-model="toCurrency" @change="getQuote()" class="input-field">
                    <template x-for="c in toCurrencies" :key="c.code">
                        <option :value="c.code" x-text="c.code + ' — ' + c.name"></option>
                    </template>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Montant à envoyer</label>
            <div class="relative">
                <input type="number" x-model="sendAmount" @input="getQuote()" min="1"
                       placeholder="Ex: 100 000"
                       class="input-field pr-20 text-lg font-semibold">
                <span x-text="fromCurrency"
                      class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-500"></span>
            </div>
        </div>

        {{-- Quote display --}}
        <div x-show="quote" x-transition class="bg-gray-50 rounded-xl p-4 space-y-2">
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">Taux de change</span>
                <span class="font-semibold text-gray-900">
                    1 <span x-text="fromCurrency"></span> = <span x-text="quote?.exchange_rate?.toFixed(6) || '—'"></span> <span x-text="toCurrency"></span>
                </span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">Frais de service</span>
                <span class="font-semibold text-gray-900">
                    <span x-text="formatAmount(quote?.fee_amount, fromCurrency)"></span>
                </span>
            </div>
            <div class="border-t border-gray-200 pt-2 flex items-center justify-between">
                <span class="text-sm font-medium text-gray-700">Total débité</span>
                <span class="font-bold text-gray-900" x-text="formatAmount(quote?.total_debit, fromCurrency)"></span>
            </div>
            <div class="bg-primary-50 rounded-lg p-3 flex items-center justify-between mt-1">
                <span class="text-sm font-semibold text-primary-700">Destinataire reçoit</span>
                <span class="text-xl font-black text-primary-600"
                      x-text="formatAmount(quote?.receive_amount, toCurrency)"></span>
            </div>
            <p class="text-xs text-gray-400 text-right">
                Taux garanti <span x-text="quote?.rate_expires_at ? 'pendant 15 min' : ''"></span>
            </p>
        </div>

        <div x-show="loading" class="flex justify-center py-4">
            <div class="w-6 h-6 border-2 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
        </div>

        <button @click="if(validateStep1()) currentStep = 2"
                :disabled="!quote || !sendAmount"
                class="btn-primary w-full" :class="{ 'opacity-50 cursor-not-allowed': !quote || !sendAmount }">
            Continuer — Choisir bénéficiaire →
        </button>
    </div>

    {{-- STEP 2: Beneficiary --}}
    <div x-show="currentStep === 2" x-transition class="card space-y-5">
        <div class="flex items-center gap-3">
            <button @click="currentStep = 1" class="p-2 rounded-lg hover:bg-gray-100">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <h2 class="text-lg font-bold text-gray-900">Choisir un bénéficiaire</h2>
        </div>

        {{-- Search --}}
        <input type="text" x-model="beneficiarySearch"
               placeholder="Rechercher un bénéficiaire..."
               class="input-field">

        {{-- Beneficiary list --}}
        <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
            <template x-for="b in filteredBeneficiaries()" :key="b.id">
                <div @click="selectBeneficiary(b)"
                     :class="selectedBeneficiary?.id === b.id ? 'border-primary-500 bg-primary-50' : 'border-gray-200 hover:border-primary-300'"
                     class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all">
                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center shrink-0">
                        <span class="text-sm font-bold text-primary-700" x-text="b.first_name[0] + b.last_name[0]"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900" x-text="b.nickname"></p>
                        <p class="text-xs text-gray-500" x-text="b.country?.name + ' · ' + b.currency?.code"></p>
                    </div>
                    <div>
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-lg" x-text="methodLabel(b.receive_method)"></span>
                    </div>
                </div>
            </template>

            <template x-if="filteredBeneficiaries().length === 0">
                <div class="text-center py-6 text-gray-400 text-sm">
                    Aucun bénéficiaire trouvé.
                </div>
            </template>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('beneficiaries.create') }}" class="btn-secondary flex-1 text-sm">
                + Ajouter un bénéficiaire
            </a>
            <button @click="if(selectedBeneficiary) currentStep = 3"
                    :disabled="!selectedBeneficiary"
                    class="btn-primary flex-1 text-sm" :class="{ 'opacity-50 cursor-not-allowed': !selectedBeneficiary }">
                Continuer →
            </button>
        </div>
    </div>

    {{-- STEP 3: Payment --}}
    <div x-show="currentStep === 3" x-transition class="card space-y-5">
        <div class="flex items-center gap-3">
            <button @click="currentStep = 2" class="p-2 rounded-lg hover:bg-gray-100">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <h2 class="text-lg font-bold text-gray-900">Méthode de paiement</h2>
        </div>

        <div class="space-y-3">
            {{-- Card --}}
            <div @click="paymentMethod = 'card'"
                 :class="paymentMethod === 'card' ? 'border-primary-500 bg-primary-50' : 'border-gray-200'"
                 class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900">Carte bancaire</p>
                    <p class="text-xs text-gray-500">Visa, Mastercard, UnionPay</p>
                </div>
                <div class="flex gap-1">
                    <span class="text-xs bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded font-bold">VISA</span>
                    <span class="text-xs bg-red-100 text-red-700 px-1.5 py-0.5 rounded font-bold">MC</span>
                </div>
            </div>

            {{-- Mobile Money --}}
            <div @click="paymentMethod = 'mobile_money'"
                 :class="paymentMethod === 'mobile_money' ? 'border-primary-500 bg-primary-50' : 'border-gray-200'"
                 class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Mobile Money</p>
                    <p class="text-xs text-gray-500">Orange Money, MTN, Wave, Moov</p>
                </div>
            </div>

            {{-- Bank Transfer --}}
            <div @click="paymentMethod = 'bank_transfer'"
                 :class="paymentMethod === 'bank_transfer' ? 'border-primary-500 bg-primary-50' : 'border-gray-200'"
                 class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Virement bancaire</p>
                    <p class="text-xs text-gray-500">IBAN / compte bancaire local</p>
                </div>
            </div>
        </div>

        {{-- Saved cards --}}
        <div x-show="paymentMethod === 'card'" class="space-y-2">
            @if(auth()->user()->cards->count())
            <p class="text-sm font-medium text-gray-700">Cartes enregistrées</p>
            @foreach(auth()->user()->cards()->active()->get() as $card)
            <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-primary-300 cursor-pointer transition-all"
                 @click="selectedCardId = {{ $card->id }}; selectedCardMethodId = '{{ $card->stripe_payment_method_id }}'">
                <div class="w-10 h-6 bg-gradient-to-r from-gray-700 to-gray-900 rounded flex items-center justify-center">
                    <span class="text-white text-xs font-bold">{{ strtoupper(substr($card->card_brand, 0, 2)) }}</span>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">•••• {{ $card->last_four }}</p>
                    <p class="text-xs text-gray-400">{{ $card->expiry }}</p>
                </div>
                @if($card->is_default)
                <span class="ml-auto text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Par défaut</span>
                @endif
            </div>
            @endforeach
            @endif
            <button class="w-full py-2.5 border-2 border-dashed border-gray-300 rounded-xl text-sm text-gray-500 hover:border-primary-400 hover:text-primary-600 transition-all">
                + Ajouter une nouvelle carte
            </button>
        </div>

        <button @click="if(paymentMethod) currentStep = 4"
                :disabled="!paymentMethod"
                class="btn-primary w-full" :class="{ 'opacity-50 cursor-not-allowed': !paymentMethod }">
            Continuer → Confirmation
        </button>
    </div>

    {{-- STEP 4: Confirmation --}}
    <div x-show="currentStep === 4" x-transition class="space-y-4">
        <div class="card">
            <div class="flex items-center gap-3 mb-4">
                <button @click="currentStep = 3" class="p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <h2 class="text-lg font-bold text-gray-900">Confirmer le transfert</h2>
            </div>

            <div class="space-y-3">
                <div class="bg-primary-50 rounded-xl p-4 text-center">
                    <p class="text-xs text-primary-500 font-medium mb-0.5">Vous envoyez</p>
                    <p class="text-3xl font-black text-primary-700" x-text="formatAmount(sendAmount, fromCurrency)"></p>
                    <p class="text-sm text-primary-500 mt-1">→ <span x-text="formatAmount(quote?.receive_amount, toCurrency)" class="font-bold text-primary-700"></span></p>
                </div>

                <div class="space-y-2.5 text-sm">
                    @foreach([['Bénéficiaire', 'selectedBeneficiary?.nickname'],['Pays', 'selectedBeneficiary?.country?.name'],['Mode réception', 'methodLabel(selectedBeneficiary?.receive_method)'],['Paiement', 'paymentMethodLabel()'],['Frais', 'formatAmount(quote?.fee_amount, fromCurrency)'],['Taux', "(quote?.exchange_rate?.toFixed(6) || '—') + ' ' + toCurrency + '/1 ' + fromCurrency"]] as $row)
                    <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                        <span class="text-gray-500">{{ $row[0] }}</span>
                        <span class="font-medium text-gray-900" x-text="{{ $row[1] }}"></span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('transfers.store') }}" id="transferForm">
            @csrf
            <input type="hidden" name="from_currency" x-bind:value="fromCurrency">
            <input type="hidden" name="to_currency" x-bind:value="toCurrency">
            <input type="hidden" name="send_amount" x-bind:value="sendAmount">
            <input type="hidden" name="beneficiary_id" x-bind:value="selectedBeneficiary?.id">
            <input type="hidden" name="payment_method" x-bind:value="paymentMethod">
            <input type="hidden" name="receive_method" x-bind:value="selectedBeneficiary?.receive_method">
            <input type="hidden" name="payment_method_id" x-bind:value="selectedCardMethodId">
        </form>

        <button @click="$refs.confirmModal.showModal()" class="btn-primary w-full">
            Confirmer et envoyer →
        </button>

        {{-- Confirm modal --}}
        <dialog x-ref="confirmModal" class="rounded-2xl shadow-2xl p-6 max-w-sm w-full backdrop:bg-black/50">
            <h3 class="text-lg font-bold text-gray-900 mb-2">Confirmer le transfert ?</h3>
            <p class="text-sm text-gray-500 mb-6">
                En confirmant, vous autorisez AfriYuan à débiter
                <strong x-text="formatAmount(quote?.total_debit, fromCurrency)"></strong>
                de votre compte.
            </p>
            <div class="flex gap-3">
                <button @click="$refs.confirmModal.close()" class="btn-secondary flex-1">Annuler</button>
                <button @click="document.getElementById('transferForm').submit()" class="btn-primary flex-1">Confirmer</button>
            </div>
        </dialog>
    </div>

</div>

@push('scripts')
<script>
function transferWizard() {
    return {
        currentStep: 1,
        direction: 'africa_to_china',
        fromCurrency: 'XOF',
        toCurrency: 'CNY',
        sendAmount: '',
        quote: null,
        loading: false,
        beneficiaries: @json($beneficiaries ?? []),
        beneficiarySearch: '',
        selectedBeneficiary: null,
        paymentMethod: '',
        selectedCardId: null,
        selectedCardMethodId: '',
        fromCurrencies: [],
        toCurrencies: [],

        init() {
            this.updateCurrencies();
            this.loadBeneficiaries();
        },

        updateCurrencies() {
            const africaCurrencies = [
                {code:'XOF',name:'CFA Ouest'},{code:'XAF',name:'CFA Centre'},
                {code:'GNF',name:'Franc Guinéen'},{code:'GHS',name:'Cedi Ghanéen'},
                {code:'LRD',name:'Dollar Libérien'},{code:'SLE',name:'Leone'}
            ];
            const cnyCurrency = [{code:'CNY',name:'Yuan Renminbi'}];
            if (this.direction === 'africa_to_china') {
                this.fromCurrencies = africaCurrencies;
                this.toCurrencies = cnyCurrency;
                this.fromCurrency = 'XOF';
                this.toCurrency = 'CNY';
            } else {
                this.fromCurrencies = cnyCurrency;
                this.toCurrencies = africaCurrencies;
                this.fromCurrency = 'CNY';
                this.toCurrency = 'XOF';
            }
            this.quote = null;
        },

        async getQuote() {
            if (!this.sendAmount || this.sendAmount < 1) return;
            this.loading = true;
            try {
                const res = await fetch('/api/v1/transfers/quote', {
                    method: 'POST',
                    headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
                    body: JSON.stringify({from_currency: this.fromCurrency, to_currency: this.toCurrency, send_amount: parseFloat(this.sendAmount)})
                });
                const data = await res.json();
                if (data.data) this.quote = data.data;
            } catch(e) {} finally { this.loading = false; }
        },

        async loadBeneficiaries() {
            try {
                const type = this.direction === 'africa_to_china' ? 'china' : 'africa';
                const res = await fetch(`/api/v1/beneficiaries?type=${type}`, {
                    headers: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}
                });
                const data = await res.json();
                this.beneficiaries = data.data || [];
            } catch(e) {}
        },

        filteredBeneficiaries() {
            if (!this.beneficiarySearch) return this.beneficiaries;
            const q = this.beneficiarySearch.toLowerCase();
            return this.beneficiaries.filter(b =>
                b.nickname?.toLowerCase().includes(q) ||
                b.first_name?.toLowerCase().includes(q) ||
                b.last_name?.toLowerCase().includes(q)
            );
        },

        selectBeneficiary(b) { this.selectedBeneficiary = b; },

        methodLabel(method) {
            return {bank_transfer:'Virement',alipay:'Alipay',wechat_pay:'WeChat Pay',cash_pickup:'Cash',mobile_money:'Mobile Money'}[method] || method;
        },

        paymentMethodLabel() {
            return {card:'Carte bancaire',mobile_money:'Mobile Money',bank_transfer:'Virement'}[this.paymentMethod] || this.paymentMethod;
        },

        formatAmount(amount, currency) {
            if (!amount || !currency) return '—';
            const decimals = ['XOF','XAF','GNF'].includes(currency) ? 0 : 2;
            const symbols = {XOF:'CFA',XAF:'CFA',GNF:'FG',GHS:'GH₵',LRD:'L$',SLE:'Le',CNY:'¥'};
            return (symbols[currency] || currency) + ' ' + new Intl.NumberFormat('fr-FR', {minimumFractionDigits: decimals, maximumFractionDigits: decimals}).format(parseFloat(amount));
        },

        validateStep1() {
            return this.quote && this.sendAmount > 0;
        }
    }
}
</script>
@endpush
@endsection
