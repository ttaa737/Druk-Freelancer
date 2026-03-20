<?php

namespace App\Services;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OTPService
{
    /**
     * Generate and send OTP via email.
     */
    public function sendEmailOTP(User $user, string $type): Otp
    {
        $otp = Otp::generate($user->email, $type, $user->id, 15);

        try {
            Mail::to($user->email)->send(new \App\Mail\OtpMail($user, $otp->code, $type));
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email: ' . $e->getMessage());
        }

        return $otp;
    }

    /**
     * Generate and send OTP via SMS.
     */
    public function sendSmsOTP(User $user, string $type): Otp
    {
        $otp = Otp::generate($user->phone, $type, $user->id, 10);

        // SMS integration placeholder - integrates with Bhutan Telecom / B-Mobile
        $this->sendSms($user->phone, "Your Druk Freelancer OTP is: {$otp->code}. Valid for 10 minutes. Do not share this code.");

        return $otp;
    }

    /**
     * Verify an OTP code.
     */
    public function verify(string $identifier, string $type, string $code): bool
    {
        $otp = Otp::where('identifier', $identifier)
                  ->where('type', $type)
                  ->where('is_used', false)
                  ->where('expires_at', '>', now())
                  ->latest()
                  ->first();

        if (!$otp) {
            return false;
        }

        $otp->increment('attempts');

        if ($otp->attempts > 5) {
            $otp->update(['is_used' => true]);
            return false;
        }

        if ($otp->code !== $code) {
            return false;
        }

        $otp->update(['is_used' => true, 'used_at' => now()]);

        return true;
    }

    /**
     * Send SMS via configured provider (stub for Bhutanese providers).
     */
    private function sendSms(string $phoneNumber, string $message): void
    {
        $driver = config('services.sms.driver', 'log');
        $apiKey = config('services.sms.api_key');
        $senderId = config('services.sms.sender_id', 'DRUKFREE');

        if ($driver === 'log' || !$apiKey) {
            Log::info("SMS to {$phoneNumber}: {$message}");
            return;
        }

        // TODO: Integrate with actual Bhutan Telecom / B-Mobile SMS API
        // HTTP call to SMS provider API endpoint
        try {
            $client = new \GuzzleHttp\Client();
            $client->post($driver, [
                'json' => [
                    'api_key'   => $apiKey,
                    'sender_id' => $senderId,
                    'to'        => $phoneNumber,
                    'message'   => $message,
                ],
                'timeout' => 10,
            ]);
        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
        }
    }
}
