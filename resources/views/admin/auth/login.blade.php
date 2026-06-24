<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion Admin — AfriYuan Backoffice</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-900 flex items-center justify-center p-4">

<div class="w-full max-w-md">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-primary-500 rounded-2xl flex items-center justify-center shadow-xl">
                <span class="text-lg font-black text-white">AY</span>
            </div>
            <span class="text-2xl font-black text-white">AfriYuan</span>
        </div>
        <p class="text-gray-400 text-sm">Accès réservé aux administrateurs</p>
    </div>

    {{-- Card --}}
    <div class="bg-gray-800 rounded-2xl p-8 shadow-2xl border border-gray-700">

        <h1 class="text-xl font-bold text-white mb-6">Connexion Backoffice</h1>

        @if (session('error'))
            <div class="mb-4 p-3 bg-red-900/50 border border-red-700 rounded-xl text-red-300 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
            @csrf

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">
                    Adresse email
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                    placeholder="admin@afriyuan.com"
                >
                @error('email')
                    <p class="mt-1.5 text-red-400 text-xs">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">
                    Mot de passe
                </label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                    placeholder="••••••••"
                >
                @error('password')
                    <p class="mt-1.5 text-red-400 text-xs">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember --}}
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember"
                    class="w-4 h-4 rounded border-gray-600 text-primary-500 focus:ring-primary-500 bg-gray-700">
                <span class="text-sm text-gray-400">Rester connecté</span>
            </label>

            {{-- Submit --}}
            <button type="submit"
                class="w-full py-3 px-4 bg-primary-600 hover:bg-primary-500 text-white font-semibold rounded-xl transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                Se connecter
            </button>
        </form>

    </div>

    <p class="text-center text-gray-600 text-xs mt-6">
        © {{ date('Y') }} AfriYuan — Accès restreint
    </p>
</div>

</body>
</html>
