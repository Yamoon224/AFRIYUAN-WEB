<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>503 — Maintenance · AfriYuan</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 flex">

    {{-- Left panel: illustration --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary-600 to-primary-800 flex-col items-center justify-center p-12 relative overflow-hidden">

        {{-- Decorative blobs --}}
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute top-24 right-24 w-52 h-52 rounded-full bg-gold-400 blur-3xl"></div>
            <div class="absolute bottom-20 left-12 w-60 h-60 rounded-full bg-white blur-3xl"></div>
            <div class="absolute top-1/3 left-1/3 w-40 h-40 rounded-full bg-primary-300 blur-2xl"></div>
        </div>

        {{-- 503 SVG Illustration --}}
        <div class="relative z-10 flex flex-col items-center">
            <svg viewBox="0 0 400 360" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full max-w-sm drop-shadow-2xl">

                {{-- Shadow --}}
                <ellipse cx="200" cy="345" rx="120" ry="12" fill="rgba(0,0,0,0.2)"/>

                {{-- Workbench --}}
                <rect x="60" y="280" width="280" height="18" rx="5" fill="rgba(255,255,255,0.15)"/>
                <rect x="80" y="298" width="12" height="40" rx="4" fill="rgba(255,255,255,0.1)"/>
                <rect x="308" y="298" width="12" height="40" rx="4" fill="rgba(255,255,255,0.1)"/>

                {{-- Main wrench (large) --}}
                <g transform="translate(175, 185) rotate(-30)">
                    {{-- Handle --}}
                    <rect x="-12" y="10" width="24" height="110" rx="8" fill="rgba(255,255,255,0.9)"/>
                    <rect x="-8" y="14" width="16" height="102" rx="5" fill="rgba(255,255,255,0.7)"/>
                    {{-- Grip texture --}}
                    <rect x="-10" y="50" width="20" height="6" rx="2" fill="rgba(200,200,200,0.4)"/>
                    <rect x="-10" y="65" width="20" height="6" rx="2" fill="rgba(200,200,200,0.4)"/>
                    <rect x="-10" y="80" width="20" height="6" rx="2" fill="rgba(200,200,200,0.4)"/>
                    <rect x="-10" y="95" width="20" height="6" rx="2" fill="rgba(200,200,200,0.4)"/>
                    {{-- Head --}}
                    <rect x="-20" y="-30" width="40" height="44" rx="8" fill="rgba(255,255,255,0.9)"/>
                    {{-- Gap (open end wrench) --}}
                    <rect x="-14" y="-30" width="28" height="18" rx="3" fill="rgba(212,19,43,0.6)"/>
                    {{-- Inner circle of open end --}}
                    <circle cx="0" cy="-2" r="10" fill="rgba(212,19,43,0.4)" stroke="rgba(255,255,255,0.9)" stroke-width="4"/>
                </g>

                {{-- Screwdriver --}}
                <g transform="translate(248, 195) rotate(25)">
                    {{-- Handle --}}
                    <rect x="-9" y="-20" width="18" height="70" rx="6" fill="#FFD700"/>
                    <rect x="-6" y="-16" width="12" height="62" rx="4" fill="rgba(255,200,0,0.8)"/>
                    {{-- Shaft --}}
                    <rect x="-3" y="50" width="6" height="80" rx="2" fill="rgba(200,200,200,0.9)"/>
                    {{-- Tip --}}
                    <path d="M-5 130 L0 142 L5 130" fill="rgba(180,180,180,0.9)"/>
                    {{-- Grip bands --}}
                    <rect x="-9" y="5" width="18" height="5" rx="1" fill="rgba(180,120,0,0.5)"/>
                    <rect x="-9" y="20" width="18" height="5" rx="1" fill="rgba(180,120,0,0.5)"/>
                    <rect x="-9" y="35" width="18" height="5" rx="1" fill="rgba(180,120,0,0.5)"/>
                </g>

                {{-- Gear 1 --}}
                <g transform="translate(148, 100) rotate(10)">
                    <circle cx="0" cy="0" r="38" fill="none" stroke="rgba(255,255,255,0.85)" stroke-width="10"/>
                    <circle cx="0" cy="0" r="16" fill="rgba(255,255,255,0.15)" stroke="rgba(255,255,255,0.85)" stroke-width="7"/>
                    <rect x="-5" y="-46" width="10" height="14" rx="2.5" fill="rgba(255,255,255,0.85)"/>
                    <rect x="-5" y="32" width="10" height="14" rx="2.5" fill="rgba(255,255,255,0.85)"/>
                    <rect x="32" y="-5" width="14" height="10" rx="2.5" fill="rgba(255,255,255,0.85)"/>
                    <rect x="-46" y="-5" width="14" height="10" rx="2.5" fill="rgba(255,255,255,0.85)"/>
                    <rect x="18" y="-42" width="10" height="14" rx="2.5" fill="rgba(255,255,255,0.85)" transform="rotate(45 23 -35)"/>
                    <rect x="-28" y="-42" width="10" height="14" rx="2.5" fill="rgba(255,255,255,0.85)" transform="rotate(-45 -23 -35)"/>
                    <rect x="18" y="28" width="10" height="14" rx="2.5" fill="rgba(255,255,255,0.85)" transform="rotate(-45 23 35)"/>
                    <rect x="-28" y="28" width="10" height="14" rx="2.5" fill="rgba(255,255,255,0.85)" transform="rotate(45 -23 35)"/>
                    <animateTransform attributeName="transform" type="rotate" from="10" to="370" dur="8s" repeatCount="indefinite" additive="sum"/>
                </g>

                {{-- Gear 2 (small, opposite spin) --}}
                <g transform="translate(220, 70) rotate(-5)">
                    <circle cx="0" cy="0" r="22" fill="none" stroke="rgba(255,215,0,0.85)" stroke-width="7"/>
                    <circle cx="0" cy="0" r="9" fill="rgba(255,215,0,0.15)" stroke="rgba(255,215,0,0.85)" stroke-width="5"/>
                    <rect x="-4" y="-28" width="8" height="10" rx="2" fill="rgba(255,215,0,0.85)"/>
                    <rect x="-4" y="18" width="8" height="10" rx="2" fill="rgba(255,215,0,0.85)"/>
                    <rect x="18" y="-4" width="10" height="8" rx="2" fill="rgba(255,215,0,0.85)"/>
                    <rect x="-28" y="-4" width="10" height="8" rx="2" fill="rgba(255,215,0,0.85)"/>
                    <rect x="10" y="-26" width="8" height="10" rx="2" fill="rgba(255,215,0,0.85)" transform="rotate(45 14 -21)"/>
                    <rect x="-18" y="-26" width="8" height="10" rx="2" fill="rgba(255,215,0,0.85)" transform="rotate(-45 -14 -21)"/>
                    <animateTransform attributeName="transform" type="rotate" from="-5" to="-365" dur="5s" repeatCount="indefinite" additive="sum"/>
                </g>

                {{-- Progress bar / maintenance indicator --}}
                <rect x="110" y="248" width="180" height="16" rx="8" fill="rgba(0,0,0,0.2)"/>
                <rect x="110" y="248" width="108" height="16" rx="8" fill="rgba(255,215,0,0.85)">
                    <animate attributeName="width" values="20;108;20" dur="3s" repeatCount="indefinite"/>
                </rect>
                <text x="200" y="260" text-anchor="middle" font-family="system-ui" font-weight="700" font-size="8" fill="rgba(0,0,0,0.5)">MAINTENANCE EN COURS</text>

                {{-- Floating particles --}}
                <circle cx="90" cy="120" r="3" fill="rgba(255,215,0,0.6)"/>
                <circle cx="330" cy="160" r="2.5" fill="rgba(255,255,255,0.5)"/>
                <circle cx="310" cy="90" r="2" fill="rgba(255,215,0,0.4)"/>
                <circle cx="75" cy="200" r="2" fill="rgba(255,255,255,0.3)"/>
            </svg>

            <div class="mt-4 text-center">
                <p class="text-7xl font-black text-white/15 leading-none select-none">503</p>
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
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-semibold border border-amber-100 mb-6">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Maintenance
            </span>

            <h1 class="text-4xl font-black text-gray-900 mb-3 leading-tight">
                Service temporairement<br>indisponible
            </h1>
            <p class="text-gray-500 text-lg mb-6 leading-relaxed">
                AfriYuan est en maintenance pour améliorer votre expérience. Nous serons de retour très bientôt.
            </p>

            {{-- Maintenance progress hint --}}
            <div class="bg-amber-50 border border-amber-100 rounded-xl px-4 py-3 mb-10 flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-amber-400 flex-shrink-0 animate-pulse"></div>
                <p class="text-amber-700 text-sm font-medium">Nos équipes travaillent activement à la restauration du service.</p>
            </div>

            {{-- Action --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="javascript:location.reload()"
                   class="flex items-center justify-center gap-2 px-6 py-3 rounded-xl border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Actualiser la page
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
