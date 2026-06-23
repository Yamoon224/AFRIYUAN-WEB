<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\KycDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KycController extends Controller
{
    public function status(Request $request): JsonResponse
    {
        $user = $request->user();
        $docs = $user->kycDocuments()->latest()->get();

        return response()->json(['data' => [
            'kyc_status' => $user->kyc_status,
            'kyc_level'  => $user->kyc_level,
            'documents'  => $docs->map(fn ($d) => [
                'id'            => $d->id,
                'document_type' => $d->document_type,
                'status'        => $d->status,
                'expires_at'    => $d->expires_at?->toDateString(),
                'uploaded_at'   => $d->created_at->toISOString(),
            ]),
        ]]);
    }

    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'document_type' => 'required|in:national_id,passport,drivers_license,residence_permit,utility_bill,selfie',
            'file'          => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'issued_country' => 'nullable|string|size:2',
            'document_number' => 'nullable|string|max:100',
            'expires_at'    => 'nullable|date|after:today',
        ]);

        $file     = $request->file('file');
        $hash     = hash_file('sha256', $file->getRealPath());
        $path     = $file->store("kyc/{$request->user()->uuid}", 's3');

        $document = $request->user()->kycDocuments()->create([
            'document_type'   => $request->document_type,
            'file_url'        => $path,
            'file_hash'       => $hash,
            'issued_country'  => $request->issued_country,
            'document_number' => $request->document_number,
            'expires_at'      => $request->expires_at,
            'status'          => 'pending',
        ]);

        // Update user KYC status to under_review
        $request->user()->update(['kyc_status' => 'under_review']);

        return response()->json([
            'message' => 'Document uploaded. Under review.',
            'data'    => ['document_id' => $document->id, 'status' => 'pending'],
        ], 201);
    }
}
