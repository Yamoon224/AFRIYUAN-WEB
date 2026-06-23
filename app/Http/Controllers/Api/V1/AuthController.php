<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(private readonly OtpService $otpService) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            ...$request->validated(),
            'preferred_language' => 'fr',
        ]);

        $this->otpService->generateAndSend($user);

        return response()->json([
            'message' => 'Account created. OTP sent.',
            'data'    => ['user_id' => $user->id, 'phone' => $user->phone_number],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
            'device_name' => 'nullable|string|max:100',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->account_status !== 'active') {
            return response()->json(['message' => 'Account suspended or banned.'], 403);
        }

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        $token = $user->createToken($request->device_name ?? 'api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'data'    => [
                'token' => $token,
                'user'  => new UserResource($user->load('country')),
            ],
        ]);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp'     => 'required|string|size:6',
            'type'    => 'required|in:phone,email',
        ]);

        $user = User::findOrFail($request->user_id);

        $verified = $this->otpService->verify($user->phone_number, $request->otp);

        if (!$verified) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 422);
        }

        if ($request->type === 'phone') {
            $user->update(['phone_verified_at' => now()]);
        }

        $token = $user->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'message' => 'Phone verified successfully.',
            'data'    => [
                'token' => $token,
                'user'  => new UserResource($user->load('country')),
            ],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'data' => new UserResource($request->user()->load('country', 'preferredCurrency')),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function resendOtp(Request $request): JsonResponse
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $user = User::findOrFail($request->user_id);
        $this->otpService->generateAndSend($user);
        return response()->json(['message' => 'OTP resent.']);
    }
}
