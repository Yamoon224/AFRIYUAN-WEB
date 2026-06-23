<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KycController extends Controller
{
    public function index()
    {
        $documents = KycDocument::with('user')
            ->where('status', 'under_review')
            ->latest()
            ->paginate(10);

        return view('admin.kyc.index', compact('documents'));
    }

    public function update(Request $request, KycDocument $document)
    {
        $request->validate([
            'status'          => 'required|in:approved,rejected',
            'rejection_note'  => 'required_if:status,rejected|nullable|string|max:500',
        ]);

        $document->update([
            'status'          => $request->status,
            'reviewer_id'     => Auth::guard('admin')->id(),
            'reviewed_at'     => now(),
            'rejection_note'  => $request->rejection_note,
        ]);

        $user = $document->user;

        if ($request->status === 'approved') {
            $allApproved = $user->kycDocuments()->where('status', '!=', 'approved')->doesntExist();
            if ($allApproved) {
                $user->update([
                    'kyc_status' => 'approved',
                    'kyc_level'  => max($user->kyc_level, 1),
                ]);
            }
        } elseif ($request->status === 'rejected') {
            $user->update(['kyc_status' => 'rejected']);
        }

        return back()->with('success', "Document " . ($request->status === 'approved' ? 'approuvé' : 'rejeté') . " avec succès.");
    }
}
