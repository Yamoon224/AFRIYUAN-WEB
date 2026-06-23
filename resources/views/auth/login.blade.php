@extends('layouts.auth')

@section('title', 'Connexion')
@section('subtitle', 'Connectez-vous à votre compte')

@section('content')
<div>
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Bon retour 👋</h1>
    <p class="text-gray-500 text-sm mb-8">Connectez-vous pour accéder à votre compte AfriYuan.</p>

    @if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
        {{ $errors->first() }}
    </div>
    @endif

    @if(session('status'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">
        {{ session('status') }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div class="fl-group">
            <input type="email" name="email" value="{{ old('email') }}" required placeholder=" "
                   class="fl-input {{ $errors->has('email') ? 'error' : '' }}">
            <label class="fl-label">Adresse email</label>
        </div>

        <div>
            <div class="fl-group" x-data="{show: false}">
                <input :type="show ? 'text' : 'password'" name="password" required placeholder=" "
                       class="fl-input pr-12 {{ $errors->has('password') ? 'error' : '' }}">
                <label class="fl-label">Mot de passe</label>
                @include('partials.eye-toggle', ['var' => 'show'])
            </div>
            <div class="flex justify-end mt-1.5">
                <a href="{{ route('password.request') }}" class="text-xs text-primary-600 hover:underline">
                    Mot de passe oublié ?
                </a>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="remember" id="remember"
                   class="rounded border-gray-300 text-primary-500 focus:ring-primary-500">
            <label for="remember" class="text-sm text-gray-600">Se souvenir de moi</label>
        </div>

        <button type="submit" class="btn-primary w-full">
            Se connecter
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Pas encore de compte ?
        <a href="{{ route('register') }}" class="text-primary-600 font-semibold hover:underline">Créer un compte</a>
    </p>
</div>
@endsection
