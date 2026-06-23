<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KycController extends Controller
{
    public function index()
    {
        $documents = Auth::user()->kycDocuments()->latest()->get();
        return view('kyc.index', compact('documents'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:passport,national_id,driving_license,utility_bill,selfie',
            'document'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $user = Auth::user();
        $file = $request->file('document');

        $path     = $file->store("kyc/{$user->uuid}", 's3');
        $hash     = hash('sha256', file_get_contents($file->getRealPath()));

        $user->kycDocuments()->create([
            'document_type' => $request->document_type,
            'file_url'      => Storage::disk('s3')->url($path),
            'file_hash'     => $hash,
            'status'        => 'under_review',
        ]);

        $user->update(['kyc_status' => 'under_review']);

        return redirect()->route('kyc.index')->with('kyc_success', 'Document soumis avec succès. Notre équipe l\'examinera sous 1 à 3 jours ouvrés.');
    }
}
