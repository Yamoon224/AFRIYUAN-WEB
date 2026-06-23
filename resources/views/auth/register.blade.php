@extends('layouts.auth')

@section('title', 'Inscription')
@section('subtitle', 'Créez votre compte')

@section('content')
@php
$dialCodes = [
    'CI' => '+225', 'GN' => '+224', 'SN' => '+221', 'GH' => '+233',
    'GA' => '+241', 'LR' => '+231', 'SL' => '+232', 'GW' => '+245', 'CN' => '+86',
];
$countriesJs = $countries->map(fn($c) => [
    'id'   => $c->id,
    'name' => $c->name,
    'code' => $c->code,
    'dial' => $dialCodes[$c->code] ?? '',
]);
@endphp

<div>
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Créer un compte</h1>
    <p class="text-gray-500 text-sm mb-6">Rejoignez AfriYuan pour envoyer de l'argent entre l'Afrique et la Chine.</p>

    @if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        <ul class="list-disc pl-4 space-y-1">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div x-data="{
        countryOpen: false,
        countrySearch: '',
        selectedCountry: null,
        countries: @js($countriesJs),
        get filteredCountries() {
            if (!this.countrySearch) return this.countries;
            const q = this.countrySearch.toLowerCase();
            return this.countries.filter(c => c.name.toLowerCase().includes(q));
        },
        flag(code) {
            return [...code.toUpperCase()].map(c =>
                String.fromCodePoint(c.charCodeAt(0) - 65 + 0x1F1E6)
            ).join('');
        },
        selectCountry(c) {
            this.selectedCountry = c;
            this.countryOpen = false;
            this.countrySearch = '';
        },
        init() {
            const old = {{ old('country_id') ?: 'null' }};
            if (old) this.selectedCountry = this.countries.find(c => c.id == old) || null;
        }
    }">
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Prénom / Nom --}}
        <div class="grid grid-cols-2 gap-3">
            <div class="fl-group">
                <input type="text" name="first_name" value="{{ old('first_name') }}" required placeholder=" "
                       class="fl-input {{ $errors->has('first_name') ? 'error' : '' }}">
                <label class="fl-label">Prénom</label>
            </div>
            <div class="fl-group">
                <input type="text" name="last_name" value="{{ old('last_name') }}" required placeholder=" "
                       class="fl-input {{ $errors->has('last_name') ? 'error' : '' }}">
                <label class="fl-label">Nom</label>
            </div>
        </div>

        {{-- Email --}}
        <div class="fl-group">
            <input type="email" name="email" value="{{ old('email') }}" required placeholder=" "
                   class="fl-input {{ $errors->has('email') ? 'error' : '' }}">
            <label class="fl-label">Adresse email</label>
        </div>

        {{-- Pays de résidence avec drapeaux --}}
        <div>
            <input type="hidden" name="country_id" :value="selectedCountry?.id ?? ''">
            <div class="relative">
                <button type="button"
                        @click="countryOpen = !countryOpen; if(countryOpen) $nextTick(() => $refs.cSearch?.focus())"
                        class="w-full px-4 pt-5 pb-2.5 rounded-xl border bg-white text-left flex items-center justify-between
                               focus:outline-none transition-all duration-150 text-sm
                               {{ $errors->has('country_id') ? 'border-red-400' : 'border-gray-200' }}"
                        :class="countryOpen
                            ? 'ring-2 ring-primary-500/20 border-primary-500'
                            : (selectedCountry ? 'border-gray-200' : '')">
                    <span x-show="selectedCountry" class="flex items-center gap-2 min-w-0">
                        <span class="text-xl leading-none shrink-0" x-text="flag(selectedCountry?.code ?? '')"></span>
                        <span class="text-gray-900 truncate" x-text="selectedCountry?.name"></span>
                        <span class="text-gray-400 font-mono shrink-0" x-text="selectedCountry?.dial"></span>
                    </span>
                    <span x-show="!selectedCountry" class="text-transparent select-none">·</span>
                    <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-150 ml-2"
                         :class="countryOpen ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <label class="absolute left-4 pointer-events-none transition-all duration-150"
                       :class="(selectedCountry || countryOpen)
                           ? 'top-1.5 text-xs ' + (countryOpen ? 'text-primary-600' : 'text-gray-500')
                           : 'top-3.5 text-sm text-gray-400'">
                    Pays de résidence
                </label>

                <div x-show="countryOpen" x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     @click.away="countryOpen = false"
                     class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                    <div class="p-2 border-b border-gray-100">
                        <input x-ref="cSearch" type="text" x-model="countrySearch"
                               placeholder="Rechercher un pays…"
                               class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg
                                      focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500/20">
                    </div>
                    <div class="max-h-52 overflow-y-auto">
                        <template x-for="c in filteredCountries" :key="c.id">
                            <button type="button" @click="selectCountry(c)"
                                    class="w-full text-left px-4 py-2.5 flex items-center gap-3
                                           hover:bg-primary-50 transition-colors duration-100"
                                    :class="selectedCountry?.id === c.id ? 'bg-primary-50' : ''">
                                <span class="text-xl w-8 shrink-0 leading-none" x-text="flag(c.code)"></span>
                                <span class="flex-1 text-sm font-medium text-gray-800" x-text="c.name"></span>
                                <span class="text-xs text-gray-400 font-mono" x-text="c.dial"></span>
                            </button>
                        </template>
                        <div x-show="filteredCountries.length === 0"
                             class="px-4 py-6 text-center text-sm text-gray-400">
                            Aucun pays trouvé
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Indicatif (auto) + Téléphone --}}
        <div class="grid grid-cols-3 gap-3">
            <div class="fl-group">
                <input type="text" name="phone_country_code" readonly placeholder=" "
                       :value="selectedCountry?.dial ?? '{{ old('phone_country_code', '') }}'"
                       class="fl-input bg-gray-50 cursor-default select-none text-center font-mono">
                <label class="fl-label">Indicatif</label>
            </div>
            <div class="col-span-2 fl-group">
                <input type="tel" name="phone_number" value="{{ old('phone_number') }}" required placeholder=" "
                       class="fl-input {{ $errors->has('phone_number') ? 'error' : '' }}">
                <label class="fl-label">Numéro de téléphone</label>
            </div>
        </div>

        {{-- Date de naissance + Nationalité --}}
        <div class="grid grid-cols-2 gap-3">
            <div class="fl-group" x-data="{hasVal: {{ old('date_of_birth') ? 'true' : 'false' }}}">
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                       @change="hasVal = !!$el.value"
                       class="fl-input {{ $errors->has('date_of_birth') ? 'error' : '' }}"
                       :class="!hasVal ? 'text-transparent' : ''">
                <label class="absolute left-4 pointer-events-none transition-all duration-150"
                       :class="hasVal ? 'top-1.5 text-xs text-gray-500' : 'top-3.5 text-sm text-gray-400'">
                    Date de naissance
                </label>
            </div>
            <div class="fl-group">
                <input type="text" name="nationality" value="{{ old('nationality') }}" required placeholder=" "
                       class="fl-input">
                <label class="fl-label">Nationalité</label>
            </div>
        </div>

        {{-- Mot de passe --}}
        <div class="fl-group" x-data="{show: false}">
            <input :type="show ? 'text' : 'password'" name="password" required placeholder=" "
                   class="fl-input pr-12 {{ $errors->has('password') ? 'error' : '' }}">
            <label class="fl-label">Mot de passe</label>
            @include('partials.eye-toggle', ['var' => 'show'])
        </div>

        {{-- Confirmer mot de passe --}}
        <div class="fl-group" x-data="{show: false}">
            <input :type="show ? 'text' : 'password'" name="password_confirmation" required placeholder=" "
                   class="fl-input pr-12">
            <label class="fl-label">Confirmer le mot de passe</label>
            @include('partials.eye-toggle', ['var' => 'show'])
        </div>

        {{-- Conditions --}}
        <div class="flex items-start gap-2">
            <input type="checkbox" name="terms" id="terms" required
                   class="mt-0.5 rounded border-gray-300 text-primary-500 focus:ring-primary-500 shrink-0">
            <label for="terms" class="text-sm text-gray-600 leading-snug">
                J'accepte les <a href="#" class="text-primary-600 hover:underline">Conditions d'utilisation</a>
                et la <a href="{{ route('privacy') }}" target="_blank" class="text-primary-600 hover:underline">Politique de confidentialité</a>
            </label>
        </div>

        <button type="submit" class="btn-primary w-full mt-2">
            Créer mon compte
        </button>
    </form>
    </div>

    <p class="mt-6 text-center text-sm text-gray-500">
        Déjà inscrit ?
        <a href="{{ route('login') }}" class="text-primary-600 font-semibold hover:underline">Se connecter</a>
    </p>
</div>
@endsection
