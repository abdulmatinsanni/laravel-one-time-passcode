<?php

namespace AbdulmatinSanni\LaravelOneTimePasscode\Concerns;

use AbdulmatinSanni\LaravelOneTimePasscode\Models\OneTimePasscode;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Trait HasOneTimePasscodes
 *
 * Provides a polymorphic one-to-one relationship between a model
 * and its associated one-time passcode (OTP).
 *
 * Usage:
 * ```php
 * class User extends Model
 * {
 *     use HasOneTimePasscodes;
 * }
 * ```
 *
 * @mixin Model
 */
class HasOneTimePasscodes
{
    /**
     * Define a polymorphic one-to-many relationship with OneTimePasscode.
     *
     * @return MorphMany<OneTimePasscode, Model>
     */
    public function oneTimePasscodes(): MorphMany
    {
        return $this->morphMany(OneTimePasscode::class, 'verifiable');
    }

    /**
     * @throws Exception
     */
    public function createOneTimePasscode(?string $purpose, ?int $otpExpiresInSeconds): OneTimePasscode
    {
        if (RateLimiter::tooManyAttempts("generate-one-time-passcode:{$this->getMorphClass()}:{$this->getKey()}", $perMinute = 5)) {
            $seconds = RateLimiter::availableIn("generate-one-time-passcode:{$this->getMorphClass()}:{$this->getKey()}");
            throw new Exception("Too many OTP generation attempts, You may try again in {$seconds} seconds.");
        }

        $otpExpiresInSeconds = $otpExpiresInSeconds ?? config('laravel-one-passcode.otp_ttl_seconds');

        $oneTimePasscode = new OneTimePasscode;
        $oneTimePasscode->token = '123456';
        $oneTimePasscode->purpose = $purpose;
        $oneTimePasscode->expires_at = now()->addSeconds($otpExpiresInSeconds);
        $oneTimePasscode->save();

        RateLimiter::increment("generate-one-time-passcode:{$this->getMorphClass()}:{$this->getKey()}");

        return $oneTimePasscode;
    }

    /**
     * Validate a given one-time passcode token.
     *
     * @throws Exception
     */
    public function validateOneTimePasscode(string $token, ?string $purpose = null): bool
    {
        $rateKey = "validate-one-time-passcode:{$this->getMorphClass()}:{$this->getKey()}";

        if (RateLimiter::tooManyAttempts($rateKey, $perMinute = 5)) {
            $seconds = RateLimiter::availableIn($rateKey);
            throw new Exception("Too many invalid OTP attempts. Please try again in {$seconds} seconds.");
        }

        $oneTimePasscode = $this->oneTimePasscodes()
            ->where('token', Crypt::encrypt($token))
            ->when($purpose, fn ($query) => $query->where('purpose', $purpose))
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $oneTimePasscode) {
            throw new Exception('Unable to validate one-time passcode.');
        }

        RateLimiter::clear($rateKey);

        $oneTimePasscode->delete();

        return true;
    }

    /**
     * Delete all one-time passcodes associated with the model.
     */
    public function deleteAllOneTimePasswords(): void
    {
        $this->oneTimePasscodes()->delete();
    }
}
