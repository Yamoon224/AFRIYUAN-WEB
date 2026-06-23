<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'first_name'         => 'sometimes|string|max:100',
            'last_name'          => 'sometimes|string|max:100',
            'address'            => 'sometimes|nullable|string',
            'city'               => 'sometimes|nullable|string|max:100',
            'preferred_language' => 'sometimes|in:fr,en,zh',
        ]);

        $request->user()->update($request->only([
            'first_name', 'last_name', 'address', 'city', 'preferred_language',
        ]));

        return response()->json([
            'message' => 'Profile updated.',
            'data'    => new UserResource($request->user()->load('country')),
        ]);
    }

    public function updatePhoto(Request $request): JsonResponse
    {
        $request->validate(['photo' => 'required|image|mimes:jpg,jpeg,png|max:2048']);

        $path = $request->file('photo')->store("profiles/{$request->user()->uuid}", 's3');
        $request->user()->update(['profile_photo_url' => $path]);

        return response()->json(['message' => 'Photo updated.', 'data' => ['url' => $path]]);
    }

    public function updatePin(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'pin'              => 'required|string|digits:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $request->user()->password)) {
            return response()->json(['message' => 'Current password incorrect.'], 422);
        }

        $request->user()->update(['pin_hash' => Hash::make($request->pin)]);
        return response()->json(['message' => 'PIN updated successfully.']);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $request->user()->password)) {
            return response()->json(['message' => 'Current password incorrect.'], 422);
        }

        $request->user()->update(['password' => $request->password]);
        return response()->json(['message' => 'Password changed successfully.']);
    }
}
