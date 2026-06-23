@extends('layouts.admin')

@section('title', 'Vérifications KYC')
@section('page-title', 'Vérifications KYC')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

    @forelse($documents as $doc)
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center text-primary-600 font-bold text-sm">
                    {{ strtoupper(substr($doc->user->first_name, 0, 1)) }}{{ strtoupper(substr($doc->user->last_name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $doc->user->full_name }}</p>
                    <p class="text-xs text-gray-400">{{ $doc->user->email }} · {{ $doc->user->country?->name }}</p>
                </div>
            </div>
            @switch($doc->status)
                @case('under_review') <span class="badge-warning">En examen</span> @break
                @case('approved') <span class="badge-success">Approuvé</span> @break
                @case('rejected') <span class="badge-danger">Rejeté</span> @break
                @default <span class="badge-gray">{{ $doc->status }}</span>
            @endswitch
        </div>

        <div class="p-5">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-sm font-medium text-gray-700">
                        {{ ['passport' => 'Passeport', 'national_id' => 'CNI', 'driving_license' => 'Permis', 'utility_bill' => 'Justificatif domicile', 'selfie' => 'Selfie'][$doc->document_type] ?? $doc->document_type }}
                    </p>
                    <p class="text-xs text-gray-400">Soumis le {{ $doc->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                <a href="{{ $doc->file_url }}" target="_blank"
                   class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg transition-colors">
                    Voir le document ↗
                </a>
            </div>

            @if($doc->status === 'under_review')
            <div class="flex gap-2 mt-4 pt-4 border-t border-gray-100">
                <form method="POST" action="{{ route('admin.kyc.update', $doc->id) }}" class="flex-1">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="approved">
                    <button type="submit"
                            class="w-full bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                        ✓ Approuver
                    </button>
                </form>
                <div class="flex-1" x-data="{ showNote: false, note: '' }">
                    <button @click="showNote = !showNote"
                            class="w-full bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                        ✕ Rejeter
                    </button>
                    <div x-show="showNote" x-cloak class="mt-2">
                        <form method="POST" action="{{ route('admin.kyc.update', $doc->id) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <textarea name="rejection_note" x-model="note" rows="2"
                                      placeholder="Motif du rejet..."
                                      class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2 resize-none focus:ring-2 focus:ring-red-200 mb-2"></textarea>
                            <button type="submit" :disabled="!note.trim()"
                                    class="w-full bg-red-500 hover:bg-red-600 disabled:opacity-50 text-white text-xs font-semibold px-3 py-2 rounded-xl transition-colors">
                                Confirmer le rejet
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm p-16 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
        <p class="text-gray-500 font-medium">Aucun document à examiner</p>
    </div>
    @endforelse
</div>

@if($documents->hasPages())
<div class="mt-5">{{ $documents->links() }}</div>
@endif
@endsection
