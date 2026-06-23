@extends('layouts.auth')

@section('title', 'Nouveau mot de passe')
@section('subtitle', 'Choisissez un nouveau mot de passe')

@section('content')
<div>
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Nouveau mot de passe</h1>
    <p class="text-gray-500 text-sm mb-6">Choisissez un mot de passe fort d'au moins 8 caractères.</p>

    @if($errors->any())
    <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        <ul class="list-disc pl-4 space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="fl-group">
            <input type="email" name="email" value="{{ old('email', $email) }}" required placeholder=" "
                   class="fl-input {{ $errors->has('email') ? 'error' : '' }}">
            <label class="fl-label">Adresse email</label>
        </div>

        <div class="fl-group" x-data="{show: false}">
            <input :type="show ? 'text' : 'password'" name="password" required placeholder=" "
                   class="fl-input pr-12 {{ $errors->has('password') ? 'error' : '' }}">
            <label class="fl-label">Nouveau mot de passe</label>
            @include('partials.eye-toggle', ['var' => 'show'])
        </div>

        <div class="fl-group" x-data="{show: false}">
            <input :type="show ? 'text' : 'password'" name="password_confirmation" required placeholder=" "
                   class="fl-input pr-12">
            <label class="fl-label">Confirmer le mot de passe</label>
            @include('partials.eye-toggle', ['var' => 'show'])
        </div>

        <button type="submit" class="btn-primary w-full">
            Réinitialiser le mot de passe
        </button>
    </form>
</div>
@endsection
