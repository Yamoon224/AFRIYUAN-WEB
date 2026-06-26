@extends('layouts.admin')

@section('title', $user->full_name)
@section('page-title', 'Fiche utilisateur')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            {{-- Avatar --}}
            <div class="w-16 h-16 rounded-2xl bg-primary-100 flex items-center justify-center text-primary-600 font-black text-xl shrink-0">
                {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
            </div>

            {{-- Name & meta --}}
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h2 class="text-xl font-bold text-gray-900">{{ $user->full_name }}</h2>
                    @if($user->account_status === 'active')
                        <span class="badge-success">Actif</span>
                    @elseif($user->account_status === 'suspended')
                        <span class="badge-danger">Suspendu</span>
                    @else
                        <span class="badge-gray">{{ $user->account_status }}</span>
                    @endif
                    @switch($user->kyc_status)
                        @case('approved') <span class="badge-success">KYC Niveau {{ $user->kyc_level }}</span> @break
                        @case('under_review') <span class="badge-warning">KYC en examen</span> @break
                        @case('rejected') <span class="badge-danger">KYC rejeté</span> @break
                        @default <span class="badge-gray">KYC en attente</span>
                    @endswitch
                </div>
                <p class="text-sm text-gray-400">
                    {{ $user->email }}
                    &nbsp;·&nbsp;
                    Inscrit le {{ $user->created_at->format('d/m/Y') }}
                    @if($user->last_login_at)
                        &nbsp;·&nbsp; Dernière connexion {{ $user->last_login_at->diffForHumans() }}
                    @endif
                </p>
                <p class="text-xs text-gray-300 mt-0.5 font-mono">{{ $user->uuid }}</p>
            </div>

            {{-- Back link --}}
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-gray-600 shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left column --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Personal info --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Informations personnelles</h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Prénom</p>
                        <p class="font-medium text-gray-800">{{ $user->first_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Nom</p>
                        <p class="font-medium text-gray-800">{{ $user->last_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Email</p>
                        <p class="font-medium text-gray-800 break-all">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Téléphone</p>
                        <p class="font-medium text-gray-800">
                            @if($user->phone_country_code)
                                +{{ ltrim($user->phone_country_code, '+') }}&nbsp;
                            @endif
                            {{ $user->phone_number ?? '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Date de naissance</p>
                        <p class="font-medium text-gray-800">{{ $user->date_of_birth?->format('d/m/Y') ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Genre</p>
                        <p class="font-medium text-gray-800">
                            @if($user->gender === 'male') Homme
                            @elseif($user->gender === 'female') Femme
                            @else {{ $user->gender ?? '—' }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Nationalité</p>
                        <p class="font-medium text-gray-800">{{ $user->nationality ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Pays d'inscription</p>
                        <p class="font-medium text-gray-800">{{ $user->country?->name ?? '—' }}</p>
                    </div>
                    @if($user->address || $user->city)
                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-400 mb-0.5">Adresse</p>
                        <p class="font-medium text-gray-800">{{ implode(', ', array_filter([$user->address, $user->city])) }}</p>
                    </div>
                    @endif
                    @if($user->last_login_ip)
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Dernière IP</p>
                        <p class="font-mono text-gray-700 text-xs">{{ $user->last_login_ip }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Recent transactions --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Transferts récents</h3>
                    <span class="text-xs text-gray-400">10 derniers</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wide">
                            <tr>
                                <th class="px-5 py-3 text-left">Référence</th>
                                <th class="px-4 py-3 text-left">Envoi</th>
                                <th class="px-4 py-3 text-left">Réception</th>
                                <th class="px-4 py-3 text-left">Statut</th>
                                <th class="px-4 py-3 text-left">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($user->transactions as $tx)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $tx->reference_number }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-800">
                                    {{ number_format($tx->send_amount, 2) }}
                                    <span class="text-xs font-normal text-gray-400">{{ $tx->send_currency }}</span>
                                </td>
                                <td class="px-4 py-3 font-semibold text-gray-800">
                                    {{ number_format($tx->receive_amount, 2) }}
                                    <span class="text-xs font-normal text-gray-400">{{ $tx->receive_currency }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @switch($tx->status)
                                        @case('completed') <span class="badge-success">Terminé</span> @break
                                        @case('processing') <span class="badge-warning">En cours</span> @break
                                        @case('payment_pending') <span class="badge-warning">Paiement en attente</span> @break
                                        @case('cancelled') <span class="badge-gray">Annulé</span> @break
                                        @case('failed') <span class="badge-danger">Échoué</span> @break
                                        @default <span class="badge-gray">{{ $tx->status }}</span>
                                    @endswitch
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-400">
                                    {{ $tx->initiated_at?->format('d/m/Y H:i') ?? $tx->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 text-gray-400">Aucun transfert</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Right column --}}
        <div class="space-y-6">

            {{-- Account status action --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Statut du compte</h3>
                </div>
                <div class="p-5">
                    <form method="POST" action="{{ route('admin.users.status', $user) }}">
                        @csrf
                        @method('PATCH')
                        <select name="account_status"
                                class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 mb-3 focus:ring-2 focus:ring-primary-500/30">
                            <option value="active"    {{ $user->account_status === 'active'    ? 'selected' : '' }}>Actif</option>
                            <option value="suspended" {{ $user->account_status === 'suspended' ? 'selected' : '' }}>Suspendu</option>
                            <option value="closed"    {{ $user->account_status === 'closed'    ? 'selected' : '' }}>Clôturé</option>
                        </select>
                        <button type="submit" class="btn-primary w-full text-sm">Mettre à jour</button>
                    </form>

                    @if(session('success'))
                    <p class="mt-3 text-xs text-green-600 bg-green-50 rounded-lg px-3 py-2">{{ session('success') }}</p>
                    @endif
                </div>
            </div>

            {{-- KYC Documents --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Documents KYC</h3>
                </div>
                <div class="p-5 space-y-3">
                    @forelse($user->kycDocuments as $doc)
                    <div class="rounded-xl border border-gray-100 p-3 text-sm">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <p class="font-medium text-gray-800 capitalize">{{ str_replace('_', ' ', $doc->document_type) }}</p>
                            @switch($doc->status)
                                @case('approved') <span class="badge-success text-xs">Approuvé</span> @break
                                @case('rejected') <span class="badge-danger text-xs">Rejeté</span> @break
                                @case('under_review') <span class="badge-warning text-xs">En examen</span> @break
                                @default <span class="badge-gray text-xs">En attente</span>
                            @endswitch
                        </div>
                        @if($doc->document_number)
                        <p class="text-xs text-gray-400">N° {{ $doc->document_number }}</p>
                        @endif
                        @if($doc->expires_at)
                        <p class="text-xs {{ $doc->isExpired() ? 'text-red-400' : 'text-gray-400' }}">
                            Expire : {{ $doc->expires_at->format('d/m/Y') }}
                            @if($doc->isExpired()) · <span class="font-semibold">Expiré</span> @endif
                        </p>
                        @endif
                        @if($doc->file_url)
                        <a href="{{ $doc->file_url }}" target="_blank"
                           class="inline-flex items-center gap-1 mt-1.5 text-xs text-primary-500 hover:underline">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Voir le document
                        </a>
                        @endif
                        @if($doc->reviewer_notes)
                        <p class="mt-1.5 text-xs text-gray-400 italic">Note : {{ $doc->reviewer_notes }}</p>
                        @endif
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 text-center py-4">Aucun document soumis</p>
                    @endforelse
                </div>
            </div>

            {{-- Cards --}}
            @if($user->cards->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Cartes enregistrées</h3>
                </div>
                <div class="p-5 space-y-2">
                    @foreach($user->cards as $card)
                    <div class="flex items-center gap-3 rounded-xl border border-gray-100 px-3 py-2.5 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-700">•••• {{ $card->last_four ?? '?????' }}</p>
                            <p class="text-xs text-gray-400">{{ $card->brand ?? 'Carte' }}</p>
                        </div>
                        @if(isset($card->is_active))
                            @if($card->is_active)
                                <span class="badge-success text-xs">Active</span>
                            @else
                                <span class="badge-gray text-xs">Inactive</span>
                            @endif
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
