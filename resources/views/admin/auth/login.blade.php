@extends('layouts.auth')

@section('title', 'Connexion Admin')
@section('subtitle', 'Accès réservé aux administrateurs')

@section('content')
<div>
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Backoffice Admin</h1>
    <p class="text-gray-500 text-sm mb-8">Connectez-vous pour accéder à l'espace d'administration.</p>

    @if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
        {{ $errors->first() }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
        {{ session('error') }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
        @csrf

        <div class="fl-group">
            <input type="email" name="email" value="{{ old('email') }}" required placeholder=" "
                   class="fl-input {{ $errors->has('email') ? 'error' : '' }}">
            <label class="fl-label">Adresse email</label>
        </div>

        <div class="fl-group" x-data="{ show: false }">
            <input :type="show ? 'text' : 'password'" name="password" required placeholder=" "
                   class="fl-input pr-12 {{ $errors->has('password') ? 'error' : '' }}">
            <label class="fl-label">Mot de passe</label>
            @include('partials.eye-toggle', ['var' => 'show'])
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="remember" id="remember"
                   class="rounded border-gray-300 text-primary-500 focus:ring-primary-500">
            <label for="remember" class="text-sm text-gray-600">Se souvenir de moi</label>
        </div>

        <button type="submit" class="btn-primary w-full">
            Accéder au backoffice
        </button>
    </form>
</div>
@endsection
