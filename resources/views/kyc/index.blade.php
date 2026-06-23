@extends('layouts.app')

@section('title', 'Vérification KYC')
@section('page-title', 'Vérification d\'identité')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- KYC level overview --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        @foreach([0 => ['Niveau 0', 'Non vérifié', 'Limite : 0 XOF'], 1 => ['Niveau 1', 'Basique', 'Jusqu\'à 500 000 XOF/mois'], 2 => ['Niveau 2', 'Complet', 'Illimité']] as $level => $info)
        <div class="card text-center {{ auth()->user()->kyc_level >= $level ? 'border-primary-300 bg-primary-50/30' : '' }}">
            <div class="w-10 h-10 rounded-xl mx-auto mb-2 flex items-center justify-center text-lg
                {{ auth()->user()->kyc_level > $level ? 'bg-green-100 text-green-600' :
                   (auth()->user()->kyc_level === $level ? 'bg-primary-100 text-primary-600' : 'bg-gray-100 text-gray-400') }}">
                @if(auth()->user()->kyc_level > $level) ✓
                @elseif(auth()->user()->kyc_level === $level) {{ $level }}
                @else {{ $level }}
                @endif
            </div>
            <p class="text-sm font-semibold text-gray-800">{{ $info[0] }}</p>
            <p class="text-xs text-gray-500">{{ $info[1] }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $info[2] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Current status --}}
    <div class="card mb-5">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center
                @switch(auth()->user()->kyc_status)
                    @case('approved') bg-green-100 text-green-600 @break
                    @case('under_review') bg-amber-100 text-amber-600 @break
                    @case('rejected') bg-red-100 text-red-600 @break
                    @default bg-gray-100 text-gray-500
                @endswitch">
                @switch(auth()->user()->kyc_status)
                    @case('approved')
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    @break
                    @case('under_review')
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @break
                    @default
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                @endswitch
            </div>
            <div>
                <p class="font-semibold text-gray-900">
                    @switch(auth()->user()->kyc_status)
                        @case('approved') Identité vérifiée @break
                        @case('under_review') Vérification en cours @break
                        @case('rejected') Vérification rejetée @break
                        @default Documents requis
                    @endswitch
                </p>
                <p class="text-sm text-gray-500">
                    @switch(auth()->user()->kyc_status)
                        @case('approved') Votre compte est pleinement activé. @break
                        @case('under_review') Nos équipes examinent vos documents (1–3 jours ouvrés). @break
                        @case('rejected') Vos documents n'ont pas été acceptés. Veuillez les soumettre à nouveau. @break
                        @default Soumettez vos documents pour lever les limites de transfert.
                    @endswitch
                </p>
            </div>
        </div>
    </div>

    {{-- Documents submitted --}}
    @if($documents->count())
    <div class="card mb-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Documents soumis</h3>
        <div class="space-y-3">
            @foreach($documents as $doc)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">
                            {{ ['passport' => 'Passeport', 'national_id' => 'Carte nationale d\'identité', 'driving_license' => 'Permis de conduire', 'utility_bill' => 'Justificatif de domicile', 'selfie' => 'Selfie'][$doc->document_type] ?? $doc->document_type }}
                        </p>
                        <p class="text-xs text-gray-400">{{ $doc->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
                @switch($doc->status)
                    @case('approved') <span class="badge-success">Approuvé</span> @break
                    @case('under_review') <span class="badge-warning">En examen</span> @break
                    @case('rejected') <span class="badge-danger">Rejeté</span> @break
                    @default <span class="badge-gray">En attente</span>
                @endswitch
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Upload form --}}
    @if(auth()->user()->kyc_status !== 'approved')
    <div class="card">
        <h3 class="text-sm font-semibold text-gray-900 mb-1">Soumettre un document</h3>
        <p class="text-xs text-gray-400 mb-4">Formats acceptés : JPG, PNG, PDF. Taille max : 5 MB.</p>

        @if(session('kyc_success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
            {{ session('kyc_success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('kyc.upload') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Type de document</label>
                <select name="document_type" required class="input-field">
                    <option value="">Sélectionner...</option>
                    <option value="passport" {{ old('document_type') === 'passport' ? 'selected' : '' }}>Passeport</option>
                    <option value="national_id" {{ old('document_type') === 'national_id' ? 'selected' : '' }}>Carte nationale d'identité</option>
                    <option value="driving_license" {{ old('document_type') === 'driving_license' ? 'selected' : '' }}>Permis de conduire</option>
                    <option value="utility_bill" {{ old('document_type') === 'utility_bill' ? 'selected' : '' }}>Justificatif de domicile</option>
                    <option value="selfie" {{ old('document_type') === 'selfie' ? 'selected' : '' }}>Selfie</option>
                </select>
            </div>

            <div x-data="{ dragover: false, filename: '' }">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Fichier</label>
                <label
                    @dragover.prevent="dragover = true"
                    @dragleave.prevent="dragover = false"
                    @drop.prevent="dragover = false; filename = $event.dataTransfer.files[0]?.name; $refs.fileInput.files = $event.dataTransfer.files"
                    :class="dragover ? 'border-primary-400 bg-primary-50' : 'border-gray-200 hover:border-gray-300'"
                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-xl cursor-pointer transition-colors">
                    <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <p class="text-sm text-gray-500" x-text="filename || 'Glisser-déposer ou cliquer pour sélectionner'"></p>
                    <p class="text-xs text-gray-400">JPG, PNG ou PDF jusqu\'à 5 MB</p>
                    <input type="file" name="document" x-ref="fileInput" @change="filename = $event.target.files[0]?.name" class="sr-only" accept=".jpg,.jpeg,.png,.pdf" required>
                </label>
            </div>

            <button type="submit" class="btn-primary w-full">Envoyer le document</button>
        </form>
    </div>
    @endif
</div>
@endsection
