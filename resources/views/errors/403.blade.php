<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 — Accès refusé · AfriYuan</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 flex">

    {{-- Left panel: illustration --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-gray-800 to-gray-950 flex-col items-center justify-center p-12 relative overflow-hidden">

        {{-- Decorative blobs --}}
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute top-20 right-20 w-56 h-56 rounded-full bg-primary-500 blur-3xl"></div>
            <div class="absolute bottom-20 left-10 w-48 h-48 rounded-full bg-primary-700 blur-3xl"></div>
        </div>

        {{-- Grid texture overlay --}}
        <div class="absolute inset-0 opacity-5" style="background-image: repeating-linear-gradient(0deg,transparent,transparent 40px,rgba(255,255,255,1) 40px,rgba(255,255,255,1) 41px),repeating-linear-gradient(90deg,transparent,transparent 40px,rgba(255,255,255,1) 40px,rgba(255,255,255,1) 41px);"></div>

        {{-- 403 SVG Illustration --}}
        <div class="relative z-10 flex flex-col items-center">
            <svg viewBox="0 0 400 360" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full max-w-sm drop-shadow-2xl">

                {{-- Shadow --}}
                <ellipse cx="200" cy="340" rx="100" ry="12" fill="rgba(0,0,0,0.3)"/>

                {{-- Shield body --}}
                <path d="M200 40 L310 90 L310 195 Q310 270 200 315 Q90 270 90 195 L90 90 Z" fill="#1F2937"/>
                <path d="M200 52 L298 97 L298 195 Q298 262 200 303 Q102 262 102 195 L102 97 Z" fill="#374151"/>
                {{-- Shield shine --}}
                <path d="M200 52 L298 97 L298 120 Q280 100 260 92 L200 66 L140 92 Q120 100 102 120 L102 97 Z" fill="rgba(255,255,255,0.06)"/>
                {{-- Shield border --}}
                <path d="M200 40 L310 90 L310 195 Q310 270 200 315 Q90 270 90 195 L90 90 Z" stroke="rgba(255,255,255,0.1)" stroke-width="2" fill="none"/>

                {{-- Padlock body --}}
                <rect x="158" y="170" width="84" height="72" rx="12" fill="#D4132B"/>
                <rect x="162" y="174" width="76" height="64" rx="10" fill="#AA0E22"/>
                {{-- Padlock shackle --}}
                <path d="M176 170 L176 148 Q176 120 200 120 Q224 120 224 148 L224 170" stroke="#D4132B" stroke-width="18" stroke-linecap="round" fill="none"/>
                <path d="M176 170 L176 148 Q176 120 200 120 Q224 120 224 148 L224 170" stroke="#AA0E22" stroke-width="14" stroke-linecap="round" fill="none"/>
                {{-- Keyhole --}}
                <circle cx="200" cy="198" r="12" fill="#1F2937"/>
                <rect x="196" y="205" width="8" height="18" rx="4" fill="#1F2937"/>
                {{-- Keyhole shine --}}
                <circle cx="197" cy="195" r="3" fill="rgba(255,255,255,0.15)"/>

                {{-- X marks (access denied) --}}
                <circle cx="138" cy="100" r="20" fill="rgba(212,19,43,0.15)" stroke="rgba(212,19,43,0.4)" stroke-width="1.5"/>
                <line x1="129" y1="91" x2="147" y2="109" stroke="#D4132B" stroke-width="3" stroke-linecap="round"/>
                <line x1="147" y1="91" x2="129" y2="109" stroke="#D4132B" stroke-width="3" stroke-linecap="round"/>

                <circle cx="262" cy="260" r="16" fill="rgba(212,19,43,0.15)" stroke="rgba(212,19,43,0.4)" stroke-width="1.5"/>
                <line x1="254" y1="252" x2="270" y2="268" stroke="#D4132B" stroke-width="2.5" stroke-linecap="round"/>
                <line x1="270" y1="252" x2="254" y2="268" stroke="#D4132B" stroke-width="2.5" stroke-linecap="round"/>

                {{-- Warning sparks --}}
                <circle cx="330" cy="130" r="5" fill="rgba(255,215,0,0.6)"/>
                <circle cx="72" cy="200" r="4" fill="rgba(255,215,0,0.4)"/>
                <circle cx="340" cy="240" r="3" fill="rgba(255,255,255,0.3)"/>
                <circle cx="60" cy="130" r="3" fill="rgba(255,255,255,0.25)"/>

                {{-- "403" text in shield center --}}
                <text x="200" y="225" text-anchor="middle" font-family="system-ui" font-weight="900" font-size="11" letter-spacing="1" fill="rgba(255,255,255,0.25)">ACCÈS REFUSÉ</text>
            </svg>

            <div class="mt-4 text-center">
                <p class="text-7xl font-black text-white/15 leading-none select-none">403</p>
            </div>
        </div>
    </div>

    {{-- Right panel: message --}}
    <div class="flex-1 flex flex-col items-center justify-center p-8 lg:p-16">
        <div class="w-full max-w-md">

            {{-- Mobile logo --}}
            <div class="lg:hidden flex items-center gap-2 mb-10">
                <div class="w-10 h-10 bg-primary-500 rounded-xl flex items-center justify-center shadow">
                    <span class="text-sm font-black text-white">AY</span>
                </div>
                <span class="text-xl font-black text-primary-600">AfriYuan</span>
            </div>

            {{-- Badge --}}
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-50 text-red-600 text-xs font-semibold border border-red-100 mb-6">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                Erreur 403
            </span>

            <h1 class="text-4xl font-black text-gray-900 mb-3 leading-tight">
                Accès refusé
            </h1>
            <p class="text-gray-500 text-lg mb-4 leading-relaxed">
                Vous n'avez pas les permissions nécessaires pour accéder à cette ressource.
            </p>

            @if(isset($exception) && $exception->getMessage())
            <div class="bg-red-50 border border-red-100 rounded-xl px-4 py-3 mb-8">
                <p class="text-red-600 text-sm font-medium">{{ $exception->getMessage() }}</p>
            </div>
            @else
            <div class="mb-8"></div>
            @endif

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ url()->previous() != url()->current() ? url()->previous() : '/' }}"
                   class="flex items-center justify-center gap-2 px-6 py-3 rounded-xl border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Retour
                </a>
                <a href="/"
                   class="flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-primary-500 text-white font-semibold hover:bg-primary-600 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Accueil
                </a>
            </div>

            <div class="my-10 border-t border-gray-100"></div>

            <div class="hidden lg:flex items-center gap-2">
                <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center">
                    <span class="text-xs font-black text-white">AY</span>
                </div>
                <span class="text-sm font-black text-gray-700">AfriYuan</span>
                <span class="text-gray-300 ml-1">·</span>
                <span class="text-xs text-gray-400">Transfert d'argent Afrique–Chine</span>
            </div>
        </div>
    </div>

</body>
</html>
