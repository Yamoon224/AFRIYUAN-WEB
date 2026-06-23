@extends('layouts.admin')

@section('title', 'Utilisateurs')
@section('page-title', 'Gestion des utilisateurs')

@section('content')
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    {{-- Filters --}}
    <div class="px-6 py-4 border-b border-gray-100">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom, email, téléphone..."
                   class="text-sm border border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-primary-500/30 w-56">

            <select name="kyc_status" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-xl px-3 py-2">
                <option value="">Statut KYC</option>
                <option value="pending" {{ request('kyc_status') === 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="under_review" {{ request('kyc_status') === 'under_review' ? 'selected' : '' }}>En examen</option>
                <option value="approved" {{ request('kyc_status') === 'approved' ? 'selected' : '' }}>Approuvé</option>
                <option value="rejected" {{ request('kyc_status') === 'rejected' ? 'selected' : '' }}>Rejeté</option>
            </select>

            <select name="account_status" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-xl px-3 py-2">
                <option value="">Tous comptes</option>
                <option value="active" {{ request('account_status') === 'active' ? 'selected' : '' }}>Actifs</option>
                <option value="suspended" {{ request('account_status') === 'suspended' ? 'selected' : '' }}>Suspendus</option>
            </select>

            <button type="submit" class="btn-primary text-sm">Filtrer</button>
            @if(request()->hasAny(['q','kyc_status','account_status']))
            <a href="{{ route('admin.users.index') }}" class="text-xs text-gray-400 hover:text-gray-600">✕ Reset</a>
            @endif
        </form>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wide">
            <tr>
                <th class="px-6 py-3 text-left">Utilisateur</th>
                <th class="px-4 py-3 text-left">Pays</th>
                <th class="px-4 py-3 text-left">KYC</th>
                <th class="px-4 py-3 text-left">Statut</th>
                <th class="px-4 py-3 text-left">Transferts</th>
                <th class="px-4 py-3 text-left">Inscrit le</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50/50">
                <td class="px-6 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-primary-100 flex items-center justify-center text-primary-600 font-bold text-xs">
                            {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $user->full_name }}</p>
                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $user->country?->name }}</td>
                <td class="px-4 py-3">
                    @switch($user->kyc_status)
                        @case('approved') <span class="badge-success">Niveau {{ $user->kyc_level }}</span> @break
                        @case('under_review') <span class="badge-warning">En examen</span> @break
                        @case('rejected') <span class="badge-danger">Rejeté</span> @break
                        @default <span class="badge-gray">En attente</span>
                    @endswitch
                </td>
                <td class="px-4 py-3">
                    @if($user->account_status === 'active')
                    <span class="badge-success">Actif</span>
                    @elseif($user->account_status === 'suspended')
                    <span class="badge-danger">Suspendu</span>
                    @else
                    <span class="badge-gray">{{ $user->account_status }}</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $user->transactions_count ?? 0 }}</td>
                <td class="px-4 py-3 text-gray-400 text-xs">{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.users.show', $user->uuid) }}" class="text-primary-500 hover:underline text-xs">Voir</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-10 text-gray-400">Aucun utilisateur</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $users->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
