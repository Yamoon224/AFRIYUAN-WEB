<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — AfriYuan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: false }">

{{-- Mobile overlay --}}
<div x-show="sidebarOpen" @click="sidebarOpen=false"
     class="fixed inset-0 bg-black/40 z-20 lg:hidden" x-transition></div>

{{-- Sidebar --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-100 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:flex">

    {{-- Brand --}}
    <div class="h-16 flex items-center gap-3 px-5 border-b border-gray-100 shrink-0">
        <div class="w-9 h-9 bg-primary-500 rounded-xl flex items-center justify-center shadow">
            <span class="text-sm font-black text-white">AY</span>
        </div>
        <span class="text-lg font-black text-gray-900">AfriYuan</span>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">

        <p class="px-3 mb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Principal</p>

        <a href="{{ route('dashboard') }}"
           class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zm0 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4z"/></svg>
            Tableau de bord
        </a>

        <a href="{{ route('transfers.create') }}"
           class="sidebar-link {{ request()->routeIs('transfers.create') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            Envoyer de l'argent
        </a>

        <a href="{{ route('transfers.index') }}"
           class="sidebar-link {{ request()->routeIs('transfers.index') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Historique
        </a>

        <p class="px-3 mt-4 mb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Gestion</p>

        <a href="{{ route('beneficiaries.index') }}"
           class="sidebar-link {{ request()->routeIs('beneficiaries.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Bénéficiaires
        </a>

        <a href="{{ route('cards.index') }}"
           class="sidebar-link {{ request()->routeIs('cards.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            Mes cartes
        </a>

        <a href="{{ route('kyc.index') }}"
           class="sidebar-link {{ request()->routeIs('kyc.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Vérification KYC
        </a>

        <p class="px-3 mt-4 mb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Compte</p>

        <a href="{{ route('notifications.index') }}"
           class="sidebar-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            Notifications
        </a>

        <a href="{{ route('profile.index') }}"
           class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Mon profil
        </a>

    </nav>

    {{-- User info --}}
    <div class="p-4 border-t border-gray-100 shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-primary-100 flex items-center justify-center shrink-0">
                <span class="text-sm font-bold text-primary-700">
                    {{ strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->full_name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full text-left sidebar-link text-red-500 hover:bg-red-50 hover:text-red-600 text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Déconnexion
            </button>
        </form>
    </div>
</aside>

{{-- Main content --}}
<div class="lg:ml-64 min-h-screen flex flex-col">

    {{-- Top bar --}}
    <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-4 lg:px-6 sticky top-0 z-10 shrink-0">
        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <div class="hidden lg:block">
            <h2 class="text-base font-semibold text-gray-900">@yield('page-title', 'Tableau de bord')</h2>
            @hasSection('breadcrumb')
            <p class="text-xs text-gray-400">@yield('breadcrumb')</p>
            @endif
        </div>

        <div class="flex items-center gap-3 ml-auto">
            {{-- KYC badge --}}
            @if(auth()->user()->kyc_status !== 'approved')
            <a href="{{ route('kyc.index') }}"
               class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-yellow-50 text-yellow-700 text-xs font-medium border border-yellow-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                KYC requis
            </a>
            @endif

            {{-- Notifications --}}
            <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </a>

            {{-- Send button --}}
            <a href="{{ route('transfers.create') }}" class="btn-primary text-xs px-4 py-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Envoyer
            </a>
        </div>
    </header>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="mx-6 mt-4" x-data="{ show: true }" x-show="show" x-transition>
        <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
            <button @click="show=false" class="ml-auto text-green-600 hover:text-green-800">&times;</button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mx-6 mt-4" x-data="{ show: true }" x-show="show" x-transition>
        <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ session('error') }}
            <button @click="show=false" class="ml-auto text-red-600 hover:text-red-800">&times;</button>
        </div>
    </div>
    @endif

    {{-- Page content --}}
    <main class="flex-1 p-4 lg:p-6">
        @yield('content')
    </main>

    <footer class="py-4 px-6 text-center text-xs text-gray-400 border-t border-gray-100">
        &copy; {{ date('Y') }} AfriYuan — Transfert international Afrique ↔ Chine
    </footer>
</div>

@stack('scripts')
</body>
</html>
