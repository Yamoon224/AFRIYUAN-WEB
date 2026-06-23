<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\BeneficiaryRequest;
use App\Http\Resources\Api\V1\BeneficiaryResource;
use App\Models\Beneficiary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $beneficiaries = $request->user()
            ->beneficiaries()
            ->with(['country', 'currency'])
            ->active()
            ->when($request->type, fn ($q) => $q->where('beneficiary_type', $request->type))
            ->latest()
            ->get();

        return response()->json(['data' => BeneficiaryResource::collection($beneficiaries)]);
    }

    public function store(BeneficiaryRequest $request): JsonResponse
    {
        $beneficiary = $request->user()->beneficiaries()->create($request->validated());
        $beneficiary->load(['country', 'currency']);

        return response()->json([
            'message' => 'Beneficiary added.',
            'data'    => new BeneficiaryResource($beneficiary),
        ], 201);
    }

    public function show(Request $request, Beneficiary $beneficiary): JsonResponse
    {
        abort_if($beneficiary->user_id !== $request->user()->id, 403);
        $beneficiary->load(['country', 'currency']);
        return response()->json(['data' => new BeneficiaryResource($beneficiary)]);
    }

    public function update(BeneficiaryRequest $request, Beneficiary $beneficiary): JsonResponse
    {
        abort_if($beneficiary->user_id !== $request->user()->id, 403);
        $beneficiary->update($request->validated());
        return response()->json(['message' => 'Beneficiary updated.', 'data' => new BeneficiaryResource($beneficiary->load(['country','currency']))]);
    }

    public function destroy(Request $request, Beneficiary $beneficiary): JsonResponse
    {
        abort_if($beneficiary->user_id !== $request->user()->id, 403);
        $beneficiary->delete();
        return response()->json(['message' => 'Beneficiary removed.']);
    }
}
