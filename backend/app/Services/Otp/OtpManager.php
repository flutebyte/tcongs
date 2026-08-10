<?php

namespace App\Services\Otp;

use App\Models\OtpCode;
use Illuminate\Support\Facades\Hash;

/**
 * Single entry point the auth controller calls for phone OTP login — mirrors
 * PaymentManager/ShippingManager's role in this codebase (one call site,
 * gateway swap is a constructor change, not a rewrite).
 */
class OtpManager
{
    private const CODE_LENGTH = 6;

    private const EXPIRY_MINUTES = 5;

    private const MAX_ATTEMPTS = 5;

    public function __construct(private readonly LogOtpGateway $gateway) {}

    /**
     * Generates and sends a fresh code, invalidating any still-outstanding
     * code for the same number first (so only the most recently sent code
     * ever verifies).
     */
    public function issue(string $phone): void
    {
        $code = (string) random_int(10 ** (self::CODE_LENGTH - 1), (10 ** self::CODE_LENGTH) - 1);

        OtpCode::where('phone', $phone)
            ->whereNull('consumed_at')
            ->update(['consumed_at' => now()]);

        OtpCode::create([
            'phone' => $phone,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(self::EXPIRY_MINUTES),
        ]);

        $this->gateway->send($phone, $code);
    }

    /**
     * Checks $code against the most recent unconsumed, unexpired code for
     * $phone. Consumes it (success or not, once found) so a code is single-use
     * either way — success marks it consumed immediately after verifying;
     * repeated failed attempts against the same code are capped instead of
     * consuming it outright, so a mistyped-but-correct-next-try code still works.
     */
    public function verify(string $phone, string $code): bool
    {
        $otp = OtpCode::where('phone', $phone)
            ->whereNull('consumed_at')
            ->where('expires_at', '>=', now())
            ->latest('id')
            ->first();

        if (! $otp || $otp->attempts >= self::MAX_ATTEMPTS) {
            return false;
        }

        $otp->increment('attempts');

        if (! Hash::check($code, $otp->code_hash)) {
            return false;
        }

        $otp->update(['consumed_at' => now()]);

        return true;
    }
}
