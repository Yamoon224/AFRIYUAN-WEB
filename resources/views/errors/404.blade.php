<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — Page introuvable · AfriYuan</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 flex">

    {{-- Left panel: illustration --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary-600 to-primary-800 flex-col items-center justify-center p-12 relative overflow-hidden">

        {{-- Decorative blobs --}}
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute top-16 left-16 w-48 h-48 rounded-full bg-gold-400 blur-3xl"></div>
            <div class="absolute bottom-24 right-10 w-64 h-64 rounded-full bg-white blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 rounded-full bg-primary-300 blur-3xl"></div>
        </div>

        {{-- 404 SVG Illustration --}}
        <div class="relative z-10 flex flex-col items-center">
            <svg viewBox="0 0 400 340" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full max-w-sm drop-shadow-2xl">

                {{-- Ground --}}
                <ellipse cx="200" cy="310" rx="160" ry="18" fill="rgba(0,0,0,0.15)"/>

                {{-- Winding road --}}
                <path d="M80 308 Q140 260 160 200 Q180 140 220 100 Q260 60 300 50" stroke="rgba(255,255,255,0.15)" stroke-width="28" stroke-linecap="round"/>
                <path d="M80 308 Q140 260 160 200 Q180 140 220 100 Q260 60 300 50" stroke="rgba(255,255,255,0.08)" stroke-width="32" stroke-linecap="round"/>
                {{-- Road dashes --}}
                <path d="M120 278 Q130 260 138 245" stroke="rgba(255,255,255,0.3)" stroke-width="3" stroke-dasharray="8 10" stroke-linecap="round"/>
                <path d="M155 215 Q163 198 170 182" stroke="rgba(255,255,255,0.3)" stroke-width="3" stroke-dasharray="8 10" stroke-linecap="round"/>

                {{-- Question mark signs --}}
                <rect x="280" y="100" width="68" height="68" rx="12" fill="rgba(255,215,0,0.9)" transform="rotate(-8 280 100)"/>
                <text x="314" y="146" text-anchor="middle" font-family="system-ui" font-weight="900" font-size="42" fill="#9f1239" transform="rotate(-8 314 134)">?</text>

                <rect x="60" y="140" width="52" height="52" rx="10" fill="rgba(255,255,255,0.15)" transform="rotate(6 60 140)"/>
                <text x="86" y="176" text-anchor="middle" font-family="system-ui" font-weight="900" font-size="32" fill="rgba(255,255,255,0.7)" transform="rotate(6 86 166)">?</text>

                {{-- Character body --}}
                <circle cx="200" cy="175" r="28" fill="white"/>
                <circle cx="200" cy="175" r="24" fill="#FFECD2"/>
                {{-- Face --}}
                <circle cx="192" cy="170" r="3.5" fill="#374151"/>
                <circle cx="208" cy="170" r="3.5" fill="#374151"/>
                {{-- Confused eyes (X) --}}
                <line x1="189" y1="167" x2="195" y2="173" stroke="#374151" stroke-width="2.5" stroke-linecap="round"/>
                <line x1="195" y1="167" x2="189" y2="173" stroke="#374151" stroke-width="2.5" stroke-linecap="round"/>
                <line x1="205" y1="167" x2="211" y2="173" stroke="#374151" stroke-width="2.5" stroke-linecap="round"/>
                <line x1="211" y1="167" x2="205" y2="173" stroke="#374151" stroke-width="2.5" stroke-linecap="round"/>
                {{-- Mouth (confused) --}}
                <path d="M193 182 Q200 178 207 182" stroke="#374151" stroke-width="2" stroke-linecap="round" fill="none"/>
                {{-- Hair --}}
                <path d="M175 168 Q178 148 200 146 Q222 148 225 168" fill="#1F2937"/>
                <path d="M197 146 Q200 138 203 146" stroke="#1F2937" stroke-width="3" stroke-linecap="round"/>

                {{-- Body --}}
                <rect x="182" y="202" width="36" height="50" rx="10" fill="#DC2626"/>
                {{-- AfriYuan logo on shirt --}}
                <rect x="192" y="212" width="16" height="16" rx="3" fill="#FFD700"/>
                <text x="200" y="224" text-anchor="middle" font-family="system-ui" font-weight="900" font-size="8" fill="#9f1239">AY</text>

                {{-- Arms --}}
                <path d="M182 215 Q162 225 158 240" stroke="#FFECD2" stroke-width="14" stroke-linecap="round"/>
                <path d="M218 215 Q238 225 242 240" stroke="#FFECD2" stroke-width="14" stroke-linecap="round"/>
                {{-- Hands holding map --}}
                <circle cx="153" cy="244" r="10" fill="#FFECD2"/>
                <circle cx="247" cy="244" r="10" fill="#FFECD2"/>

                {{-- Map --}}
                <rect x="158" y="232" width="84" height="60" rx="6" fill="#FEF9C3" transform="rotate(-3 200 262)"/>
                <rect x="160" y="234" width="84" height="60" rx="6" fill="#FFFDE7" transform="rotate(-3 202 264)"/>
                {{-- Map lines --}}
                <path d="M172 248 Q188 244 200 252 Q212 260 228 254" stroke="#D1D5DB" stroke-width="2" stroke-linecap="round" transform="rotate(-3 200 252)"/>
                <path d="M175 260 Q190 256 205 264" stroke="#D1D5DB" stroke-width="1.5" stroke-linecap="round" transform="rotate(-3 190 262)"/>
                <circle cx="200" cy="256" r="4" fill="#EF4444" transform="rotate(-3 200 256)"/>
                <line x1="200" y1="252" x2="200" y2="242" stroke="#EF4444" stroke-width="1.5" transform="rotate(-3 200 247)"/>

                {{-- Legs --}}
                <rect x="186" y="248" width="14" height="40" rx="7" fill="#1E3A5F"/>
                <rect x="200" y="248" width="14" height="40" rx="7" fill="#1E3A5F"/>
                {{-- Shoes --}}
                <ellipse cx="192" cy="290" rx="12" ry="7" fill="#111827"/>
                <ellipse cx="206" cy="292" rx="12" ry="7" fill="#111827"/>

                {{-- Floating stars --}}
                <circle cx="140" cy="120" r="3" fill="rgba(255,215,0,0.7)"/>
                <circle cx="270" cy="190" r="2" fill="rgba(255,255,255,0.6)"/>
                <circle cx="310" cy="150" r="2.5" fill="rgba(255,215,0,0.5)"/>
                <circle cx="100" cy="200" r="2" fill="rgba(255,255,255,0.4)"/>
            </svg>

            {{-- Error number --}}
            <div class="mt-6 text-center">
                <p class="text-8xl font-black text-white/20 leading-none select-none">404</p>
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
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary-50 text-primary-600 text-xs font-semibold border border-primary-100 mb-6">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Erreur 404
            </span>

            <h1 class="text-4xl font-black text-gray-900 mb-3 leading-tight">
                Page introuvable
            </h1>
            <p class="text-gray-500 text-lg mb-10 leading-relaxed">
                Vous semblez vous être perdu en chemin. La page que vous cherchez n'existe pas ou a été déplacée.
            </p>

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

            {{-- Separator --}}
            <div class="my-10 border-t border-gray-100"></div>

            {{-- Logo desktop --}}
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
