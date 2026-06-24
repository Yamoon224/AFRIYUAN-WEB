<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AfriYuan') — @yield('subtitle', 'International Money Transfer')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-red-50">

<div class="min-h-screen flex">
    {{-- Left panel: branding --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary-600 to-primary-800 flex-col items-center justify-center p-12 relative overflow-hidden">
        {{-- Background pattern --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-20 w-40 h-40 rounded-full bg-gold-500 blur-3xl"></div>
            <div class="absolute bottom-20 right-20 w-60 h-60 rounded-full bg-white blur-3xl"></div>
        </div>

        <div class="relative z-10 text-center text-white max-w-md">
            {{-- Logo --}}
            <div class="flex items-center justify-center gap-3 mb-8">
                <div class="w-14 h-14 bg-gold-500 rounded-2xl flex items-center justify-center shadow-xl">
                    <span class="text-2xl font-black text-primary-700">AY</span>
                </div>
                <span class="text-3xl font-black tracking-tight">AfriYuan</span>
            </div>

            <h1 class="text-4xl font-bold mb-4 leading-tight">
                Transférez en<br>toute confiance
            </h1>
            <p class="text-primary-200 text-lg leading-relaxed mb-10">
                Envoyez de l'argent entre l'Afrique et la Chine instantanément. Taux compétitifs, sécurité garantie.
            </p>

            {{-- Corridor cards --}}
            <div class="grid grid-cols-2 gap-3 text-left">
                @foreach([['🇨🇮','Côte d\'Ivoire','XOF'],['🇸🇳','Sénégal','XOF'],['🇬🇭','Ghana','GHS'],['🇬🇳','Guinée','GNF']] as $c)
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                    <div class="text-xl mb-0.5">{{ $c[0] }}</div>
                    <div class="text-sm font-semibold">{{ $c[1] }}</div>
                    <div class="text-xs text-primary-200">{{ $c[2] }} → ¥ CNY</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right panel: form --}}
    <div class="flex-1 flex flex-col items-center justify-center p-6 lg:p-12">
        {{-- Mobile logo --}}
        <div class="lg:hidden flex items-center gap-2 mb-8">
            <div class="w-10 h-10 bg-primary-500 rounded-xl flex items-center justify-center">
                <span class="text-sm font-black text-white">AY</span>
            </div>
            <span class="text-xl font-black text-primary-600">AfriYuan</span>
        </div>

        <div class="w-full max-w-md">
            @yield('content')
        </div>

        <p class="mt-8 text-xs text-gray-400 text-center">
            &copy; {{ date('Y') }} AfriYuan · Tous droits réservés ·
            <a href="{{ route('privacy') }}" target="_blank" class="hover:text-gray-600">Politique de confidentialité</a>
        </p>
    </div>
</div>

</body>
</html>
