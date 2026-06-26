<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 — Erreur serveur · AfriYuan</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 flex">

    {{-- Left panel: illustration --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-orange-600 to-red-700 flex-col items-center justify-center p-12 relative overflow-hidden">

        {{-- Decorative blobs --}}
        <div class="absolute inset-0 opacity-15 pointer-events-none">
            <div class="absolute top-10 left-10 w-52 h-52 rounded-full bg-yellow-400 blur-3xl"></div>
            <div class="absolute bottom-16 right-8 w-60 h-60 rounded-full bg-red-900 blur-3xl"></div>
        </div>

        {{-- 500 SVG Illustration --}}
        <div class="relative z-10 flex flex-col items-center">
            <svg viewBox="0 0 400 360" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full max-w-sm drop-shadow-2xl">

                {{-- Shadow --}}
                <ellipse cx="200" cy="340" rx="110" ry="12" fill="rgba(0,0,0,0.2)"/>

                {{-- Server rack base --}}
                <rect x="100" y="200" width="200" height="120" rx="10" fill="#1F2937"/>
                <rect x="104" y="204" width="192" height="112" rx="8" fill="#111827"/>

                {{-- Server units --}}
                <rect x="114" y="214" width="172" height="28" rx="5" fill="#1F2937"/>
                <rect x="118" y="218" width="164" height="20" rx="3" fill="#0F172A"/>
                {{-- Unit 1 LEDs --}}
                <circle cx="268" cy="228" r="3" fill="#EF4444">
                    <animate attributeName="opacity" values="1;0.2;1" dur="1.2s" repeatCount="indefinite"/>
                </circle>
                <circle cx="278" cy="228" r="3" fill="#EF4444">
                    <animate attributeName="opacity" values="0.2;1;0.2" dur="1.2s" repeatCount="indefinite"/>
                </circle>
                <circle cx="258" cy="228" r="3" fill="#F59E0B"/>
                <rect x="126" y="222" width="60" height="4" rx="2" fill="#1F2937"/>
                <rect x="126" y="228" width="40" height="3" rx="1.5" fill="#1F2937"/>

                <rect x="114" y="248" width="172" height="28" rx="5" fill="#1F2937"/>
                <rect x="118" y="252" width="164" height="20" rx="3" fill="#0F172A"/>
                {{-- Unit 2 LEDs --}}
                <circle cx="268" cy="262" r="3" fill="#22C55E"/>
                <circle cx="278" cy="262" r="3" fill="#EF4444">
                    <animate attributeName="opacity" values="1;0;1" dur="0.8s" repeatCount="indefinite"/>
                </circle>
                <rect x="126" y="256" width="80" height="4" rx="2" fill="#1F2937"/>
                <rect x="126" y="262" width="55" height="3" rx="1.5" fill="#1F2937"/>

                <rect x="114" y="282" width="172" height="28" rx="5" fill="#1F2937"/>
                <rect x="118" y="286" width="164" height="20" rx="3" fill="#0F172A"/>
                {{-- Unit 3 LEDs --}}
                <circle cx="268" cy="296" r="3" fill="#EF4444">
                    <animate attributeName="opacity" values="1;0.1;1" dur="0.6s" repeatCount="indefinite"/>
                </circle>
                <circle cx="278" cy="296" r="3" fill="#EF4444">
                    <animate attributeName="opacity" values="0.1;1;0.1" dur="0.6s" repeatCount="indefinite"/>
                </circle>
                <circle cx="258" cy="296" r="3" fill="#EF4444">
                    <animate attributeName="opacity" values="1;0.3;1" dur="0.9s" repeatCount="indefinite"/>
                </circle>
                <rect x="126" y="290" width="70" height="4" rx="2" fill="#1F2937"/>
                <rect x="126" y="296" width="45" height="3" rx="1.5" fill="#1F2937"/>

                {{-- Broken gear 1 (large, tilted) --}}
                <g transform="translate(200, 120) rotate(15)">
                    <circle cx="0" cy="0" r="52" fill="none" stroke="rgba(255,255,255,0.9)" stroke-width="12"/>
                    <circle cx="0" cy="0" r="22" fill="rgba(255,150,50,0.3)" stroke="rgba(255,255,255,0.9)" stroke-width="8"/>
                    {{-- Teeth --}}
                    <rect x="-7" y="-64" width="14" height="18" rx="3" fill="rgba(255,255,255,0.9)"/>
                    <rect x="-7" y="46" width="14" height="18" rx="3" fill="rgba(255,255,255,0.9)"/>
                    <rect x="46" y="-7" width="18" height="14" rx="3" fill="rgba(255,255,255,0.9)"/>
                    <rect x="-64" y="-7" width="18" height="14" rx="3" fill="rgba(255,255,255,0.9)"/>
                    {{-- Diagonal teeth --}}
                    <rect x="26" y="-58" width="14" height="18" rx="3" fill="rgba(255,255,255,0.9)" transform="rotate(45 33 -49)"/>
                    <rect x="-40" y="-58" width="14" height="18" rx="3" fill="rgba(255,255,255,0.9)" transform="rotate(-45 -33 -49)"/>
                    <rect x="26" y="40" width="14" height="18" rx="3" fill="rgba(255,255,255,0.9)" transform="rotate(-45 33 49)"/>
                    <rect x="-40" y="40" width="14" height="18" rx="3" fill="rgba(255,255,255,0.9)" transform="rotate(45 -33 49)"/>
                    {{-- Crack --}}
                    <path d="M-5 -22 L2 -5 L-8 8 L0 22" stroke="#EF4444" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </g>

                {{-- Broken gear 2 (small) --}}
                <g transform="translate(128, 145) rotate(-20)">
                    <circle cx="0" cy="0" r="28" fill="none" stroke="rgba(255,255,255,0.7)" stroke-width="8"/>
                    <circle cx="0" cy="0" r="11" fill="rgba(255,100,50,0.2)" stroke="rgba(255,255,255,0.7)" stroke-width="5"/>
                    <rect x="-5" y="-36" width="10" height="12" rx="2" fill="rgba(255,255,255,0.7)"/>
                    <rect x="-5" y="24" width="10" height="12" rx="2" fill="rgba(255,255,255,0.7)"/>
                    <rect x="24" y="-5" width="12" height="10" rx="2" fill="rgba(255,255,255,0.7)"/>
                    <rect x="-36" y="-5" width="12" height="10" rx="2" fill="rgba(255,255,255,0.7)"/>
                    {{-- Crack --}}
                    <path d="M-3 -11 L1 -2 L-4 5 L0 11" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </g>

                {{-- Sparks / lightning --}}
                <path d="M220 58 L210 80 L226 80 L214 102" stroke="#FFD700" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M280 90 L274 104 L284 104 L278 118" stroke="#FFD700" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>

                {{-- Flying bolt --}}
                <circle cx="320" cy="80" r="6" fill="rgba(255,215,0,0.5)"/>
                <circle cx="80" cy="170" r="4" fill="rgba(255,215,0,0.4)"/>
                <circle cx="340" cy="180" r="3" fill="rgba(255,255,255,0.3)"/>

                {{-- Smoke puffs --}}
                <circle cx="175" cy="195" r="16" fill="rgba(100,100,100,0.25)"/>
                <circle cx="192" cy="188" r="20" fill="rgba(80,80,80,0.2)"/>
                <circle cx="210" cy="193" r="14" fill="rgba(100,100,100,0.2)"/>
                <circle cx="190" cy="178" r="12" fill="rgba(80,80,80,0.15)"/>
            </svg>

            <div class="mt-4 text-center">
                <p class="text-7xl font-black text-white/15 leading-none select-none">500</p>
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
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-50 text-orange-600 text-xs font-semibold border border-orange-100 mb-6">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Erreur 500
            </span>

            <h1 class="text-4xl font-black text-gray-900 mb-3 leading-tight">
                Erreur serveur
            </h1>
            <p class="text-gray-500 text-lg mb-10 leading-relaxed">
                Quelque chose s'est mal passé de notre côté. Notre équipe a été alertée et travaille à résoudre le problème.
            </p>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="javascript:location.reload()"
                   class="flex items-center justify-center gap-2 px-6 py-3 rounded-xl border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Réessayer
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
