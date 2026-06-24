<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AfriYuan') — Politique de confidentialité</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-900 antialiased">

<header class="border-b border-gray-100 py-4 px-6 sticky top-0 bg-white/90 backdrop-blur-sm z-10">
    <div class="max-w-3xl mx-auto flex items-center justify-between">
        <a href="{{ url('/') }}" class="flex items-center gap-2">
            <div class="w-9 h-9 bg-primary-500 rounded-xl flex items-center justify-center shadow">
                <span class="text-sm font-black text-white">AY</span>
            </div>
            <span class="text-lg font-black text-gray-900">AfriYuan</span>
        </a>
        <a href="{{ route('login') }}" class="text-sm text-primary-600 font-semibold hover:underline">
            Se connecter →
        </a>
    </div>
</header>

<main class="max-w-3xl mx-auto px-6 py-12">
    @yield('content')
</main>

<footer class="border-t border-gray-100 py-8 text-center text-sm text-gray-400">
    &copy; {{ date('Y') }} AfriYuan · Tous droits réservés ·
    <a href="{{ route('register') }}" class="hover:text-gray-600">Créer un compte</a>
</footer>

</body>
</html>
