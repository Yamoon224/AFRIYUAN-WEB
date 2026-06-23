@extends('layouts.auth')

@section('title', 'Mot de passe oublié')
@section('subtitle', 'Réinitialisez votre accès')

@section('content')
<div>
    <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
        <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
        </svg>
    </div>

    <h1 class="text-2xl font-bold text-gray-900 mb-2 text-center">Mot de passe oublié</h1>
    <p class="text-gray-500 text-sm mb-6 text-center">
        Saisissez votre email et nous vous enverrons un lien de réinitialisation.
    </p>

    @if(session('status'))
    <div class="mb-5 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
        {{ session('status') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <div class="fl-group">
            <input type="email" name="email" value="{{ old('email') }}" required placeholder=" "
                   autofocus class="fl-input {{ $errors->has('email') ? 'error' : '' }}">
            <label class="fl-label">Adresse email</label>
        </div>

        <button type="submit" class="btn-primary w-full">
            Envoyer le lien de réinitialisation
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        <a href="{{ route('login') }}" class="text-primary-600 font-semibold hover:underline">← Retour à la connexion</a>
    </p>
</div>
@endsection
