@extends('layouts.app')

@section('title', 'Mon profil')
@section('page-title', 'Mon profil')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

    {{-- Avatar --}}
    <div class="card">
        <div class="flex items-center gap-5">
            <div class="relative">
                @if(auth()->user()->profile_photo_url)
                <img src="{{ auth()->user()->profile_photo_url }}" class="w-20 h-20 rounded-2xl object-cover">
                @else
                <div class="w-20 h-20 rounded-2xl bg-primary-100 flex items-center justify-center text-primary-600 font-bold text-2xl">
                    {{ strtoupper(substr(auth()->user()->first_name,0,1).substr(auth()->user()->last_name,0,1)) }}
                </div>
                @endif
                <form method="POST" action="{{ route('profile.updatePhoto') }}" enctype="multipart/form-data" id="photo-form">
                    @csrf @method('PATCH')
                    <label class="absolute -bottom-1 -right-1 w-7 h-7 bg-white shadow-md border border-gray-200 rounded-lg flex items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <input type="file" name="photo" accept="image/*" class="sr-only" onchange="document.getElementById('photo-form').submit()">
                    </label>
                </form>
            </div>
            <div>
                <p class="text-xl font-bold text-gray-900">{{ auth()->user()->full_name }}</p>
                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                <div class="flex items-center gap-2 mt-2">
                    @switch(auth()->user()->kyc_status)
                        @case('approved') <span class="badge-success">KYC approuvé</span> @break
                        @case('under_review') <span class="badge-warning">KYC en cours</span> @break
                        @default <span class="badge-gray">KYC requis</span>
                    @endswitch
                    <span class="badge-info">Niveau {{ auth()->user()->kyc_level }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Informations personnelles --}}
    <div class="card">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Informations personnelles</h3>

        @if(session('profile_success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">{{ session('profile_success') }}</div>
        @endif
        @if($errors->hasAny(['first_name','last_name','email','nationality','date_of_birth']))
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
            <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf @method('PATCH')

            <div class="grid grid-cols-2 gap-4">
                <div class="fl-group">
                    <input type="text" name="first_name" placeholder=" " required
                           value="{{ old('first_name', auth()->user()->first_name) }}"
                           class="fl-input">
                    <label class="fl-label">Prénom</label>
                </div>
                <div class="fl-group">
                    <input type="text" name="last_name" placeholder=" " required
                           value="{{ old('last_name', auth()->user()->last_name) }}"
                           class="fl-input">
                    <label class="fl-label">Nom</label>
                </div>
            </div>

            <div class="fl-group">
                <input type="email" name="email" placeholder=" " required
                       value="{{ old('email', auth()->user()->email) }}"
                       class="fl-input">
                <label class="fl-label">Adresse email</label>
            </div>

            <div class="fl-group">
                <input type="tel" placeholder=" " disabled
                       value="{{ auth()->user()->phone_number }}"
                       class="fl-input opacity-60 cursor-not-allowed bg-gray-50">
                <label class="fl-label" style="top:.35rem;font-size:.7rem;color:#6b7280;">Téléphone</label>
                <p class="text-xs text-gray-400 mt-1">Le numéro de téléphone ne peut pas être modifié.</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="fl-group">
                    <input type="text" name="nationality" placeholder=" "
                           value="{{ old('nationality', auth()->user()->nationality) }}"
                           class="fl-input">
                    <label class="fl-label">Nationalité</label>
                </div>
                <div class="fl-group" x-data="{hasVal: {{ auth()->user()->date_of_birth ? 'true' : 'false' }}}">
                    <input type="date" name="date_of_birth"
                           value="{{ old('date_of_birth', auth()->user()->date_of_birth?->format('Y-m-d')) }}"
                           @change="hasVal = !!$el.value"
                           class="fl-input" :class="!hasVal ? 'text-transparent' : ''">
                    <label class="absolute left-4 pointer-events-none transition-all duration-150"
                           :class="hasVal ? 'top-1.5 text-xs text-gray-500' : 'top-3.5 text-sm text-gray-400'">
                        Date de naissance
                    </label>
                </div>
            </div>

            <div class="pt-1">
                <button type="submit" class="btn-primary">Enregistrer les modifications</button>
            </div>
        </form>
    </div>

    {{-- Changer le mot de passe --}}
    <div class="card">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Changer le mot de passe</h3>

        @if(session('password_success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">{{ session('password_success') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.changePassword') }}" class="space-y-4">
            @csrf @method('PATCH')

            <div class="fl-group" x-data="{show: false}">
                <input :type="show ? 'text' : 'password'" name="current_password" required placeholder=" "
                       class="fl-input pr-12 {{ $errors->has('current_password') ? 'error' : '' }}">
                <label class="fl-label">Mot de passe actuel</label>
                @include('partials.eye-toggle', ['var' => 'show'])
            </div>
            @error('current_password') <p class="text-xs text-red-500 -mt-2">{{ $message }}</p> @enderror

            <div class="fl-group" x-data="{show: false}">
                <input :type="show ? 'text' : 'password'" name="password" required placeholder=" "
                       class="fl-input pr-12 {{ $errors->has('password') ? 'error' : '' }}">
                <label class="fl-label">Nouveau mot de passe</label>
                @include('partials.eye-toggle', ['var' => 'show'])
            </div>
            @error('password') <p class="text-xs text-red-500 -mt-2">{{ $message }}</p> @enderror

            <div class="fl-group" x-data="{show: false}">
                <input :type="show ? 'text' : 'password'" name="password_confirmation" required placeholder=" "
                       class="fl-input pr-12">
                <label class="fl-label">Confirmer le nouveau mot de passe</label>
                @include('partials.eye-toggle', ['var' => 'show'])
            </div>

            <button type="submit" class="btn-secondary">Changer le mot de passe</button>
        </form>
    </div>

    {{-- PIN --}}
    <div class="card">
        <h3 class="text-sm font-semibold text-gray-900 mb-1">Code PIN de transaction</h3>
        <p class="text-xs text-gray-400 mb-4">Code à 6 chiffres utilisé pour confirmer vos transferts.</p>

        @if(session('pin_success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">{{ session('pin_success') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.updatePin') }}" class="space-y-4">
            @csrf @method('PATCH')
            <div class="grid grid-cols-2 gap-4">
                <div class="fl-group" x-data="{show: false}">
                    <input :type="show ? 'text' : 'password'" name="pin" maxlength="6"
                           pattern="[0-9]{6}" inputmode="numeric" required placeholder=" "
                           class="fl-input pr-12 tracking-[.5em] text-center text-lg">
                    <label class="fl-label">Nouveau PIN (6 chiffres)</label>
                    @include('partials.eye-toggle', ['var' => 'show'])
                </div>
                <div class="fl-group" x-data="{show: false}">
                    <input :type="show ? 'text' : 'password'" name="pin_confirmation" maxlength="6"
                           pattern="[0-9]{6}" inputmode="numeric" required placeholder=" "
                           class="fl-input pr-12 tracking-[.5em] text-center text-lg">
                    <label class="fl-label">Confirmer le PIN</label>
                    @include('partials.eye-toggle', ['var' => 'show'])
                </div>
            </div>
            <button type="submit" class="btn-secondary">Enregistrer le PIN</button>
        </form>
    </div>

    {{-- Zone dangereuse --}}
    <div class="card border-red-200 bg-red-50/30">
        <h3 class="text-sm font-semibold text-red-700 mb-2">Zone dangereuse</h3>
        <p class="text-xs text-gray-500 mb-3">La suppression de votre compte est définitive.</p>
        <button disabled class="text-sm text-red-500 border border-red-300 px-4 py-2 rounded-xl opacity-50 cursor-not-allowed">
            Supprimer mon compte
        </button>
    </div>
</div>
@endsection
