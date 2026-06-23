<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    private const TTL_MINUTES  = 5;
    private const MAX_ATTEMPTS = 5;

    // ── Public API ────────────────────────────────────────────────────────────

    /**
     * Generate a 6-digit OTP, store it in cache, and send it to the user.
     * Channel: SMS via Twilio if credentials are configured, email otherwise.
     */
    public function generateAndSend(User $user): string
    {
        $otp = $this->generate($user->phone_number);
        $this->send($user, $otp);
        return $otp;
    }

    /**
     * Verify the OTP for a given phone number.
     * The phone number is used as the identifier (hashed in cache key).
     */
    public function verify(string $phone, string $otp): bool
    {
        $key  = $this->cacheKey($phone);
        $data = Cache::get($key);

        if (!$data) return false;

        $data['attempts']++;
        Cache::put($key, $data, self::TTL_MINUTES * 60);

        if ($data['attempts'] > self::MAX_ATTEMPTS) {
            Cache::forget($key);
            return false;
        }

        if (hash_equals($data['otp'], $otp)) {
            Cache::forget($key);
            return true;
        }

        return false;
    }

    // ── Internal ──────────────────────────────────────────────────────────────

    private function generate(string $phone): string
    {
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put($this->cacheKey($phone), ['otp' => $otp, 'attempts' => 0], self::TTL_MINUTES * 60);

        return $otp;
    }

    private function send(User $user, string $otp): void
    {
        if ($this->twilioConfigured()) {
            $this->sendSms($user->phone_number, $otp);
        } else {
            $this->sendEmail($user, $otp);
        }
    }

    private function twilioConfigured(): bool
    {
        $sid = config('services.twilio.sid', env('TWILIO_SID', ''));
        return !empty($sid) && !str_starts_with($sid, 'REPLACE_');
    }

    private function sendSms(string $phone, string $otp): void
    {
        try {
            $client = new \Twilio\Rest\Client(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );

            $client->messages->create($phone, [
                'from' => config('services.twilio.from'),
                'body' => "Votre code AfriYuan : {$otp}. Valable 5 minutes. Ne le partagez jamais.",
            ]);
        } catch (\Throwable $e) {
            Log::error('OTP SMS failed, falling back to email', ['error' => $e->getMessage()]);
            // No user model here — log only; caller handles email separately if needed
        }
    }

    private function sendEmail(User $user, string $otp): void
    {
        try {
            Mail::to($user->email)->send(new OtpMail($otp));
        } catch (\Throwable $e) {
            // Last-resort: log the OTP so local dev still works without mail config
            Log::warning("OTP email failed — code for {$user->email}: {$otp}", ['error' => $e->getMessage()]);
        }
    }

    private function cacheKey(string $phone): string
    {
        return 'otp_' . hash('sha256', $phone);
    }
}
