<?php

namespace AbdulmatinSanni\LaravelOneTimePasscode;

use AbdulmatinSanni\LaravelOneTimePasscode\Contracts\CanUseOneTimePasscodes;
use AbdulmatinSanni\LaravelOneTimePasscode\Models\OneTimePasscode;
use AbdulmatinSanni\LaravelOneTimePasscode\Support\PasscodeGenerators\OneTimePasscodeGenerator;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\RateLimiter;
use RuntimeException;

class LaravelOneTimePasscode
{
    /**
     * @throws Exception
     */
    public static function create(
        CanUseOneTimePasscodes $verifiable,
        ?string $purpose,
        ?int $otpExpiresInSeconds,
        ?int $length = null
    ): OneTimePasscode {
        $rateLimiterKey = "generate-one-time-passcode:{$verifiable->getMorphClass()}:{$verifiable->getKey()}";

        if (RateLimiter::tooManyAttempts($rateLimiterKey, $perMinute = 5)) {
            $seconds = RateLimiter::availableIn($rateLimiterKey);
            throw new Exception("Too many OTP generation attempts, You may try again in {$seconds} seconds.");
        }

        $otpExpiresInSeconds = $otpExpiresInSeconds ?? config('laravel-one-passcode.ttl_seconds_seconds');

        $oneTimePasscode = new OneTimePasscode;
        //        $oneTimePasscode->verifiable()->associate($verifiable);
        $oneTimePasscode->token = static::getGenerator()->length($length)->generate();
        $oneTimePasscode->purpose = $purpose;
        $oneTimePasscode->expires_at = now()->addSeconds($otpExpiresInSeconds);
        $oneTimePasscode->save();

        RateLimiter::increment($rateLimiterKey);

        return $oneTimePasscode;
    }

    /**
     * @throws Exception
     */
    public static function validate(CanUseOneTimePasscodes $verifiable, string $token, ?string $purpose = null): bool
    {
        $rateLimiterKey = "validate-one-time-passcode:{$verifiable->getMorphClass()}:{$verifiable->getKey()}";

        if (RateLimiter::tooManyAttempts($rateLimiterKey, $perMinute = 5)) {
            $seconds = RateLimiter::availableIn($rateLimiterKey);
            throw new Exception("Too many invalid OTP attempts. Please try again in {$seconds} seconds.");
        }

        $oneTimePasscode = $verifiable->oneTimePasscodes()
            ->where('token', Crypt::encrypt($token))
            ->when($purpose, fn ($query) => $query->where('purpose', $purpose))
            ->latest()
            ->first();

        if (! $oneTimePasscode) {
            throw new Exception('Unable to validate OTP');
        } elseif (! $oneTimePasscode->isExpired()) {
            throw new Exception('Unable to validate OTP');
        }

        RateLimiter::clear($rateLimiterKey);

        $oneTimePasscode->delete();

        return true;
    }

    /**
     * @throws Exception
     */
    public static function getGenerator(): OneTimePasscodeGenerator
    {
        $generatorClassName = config('laravel-one-passcode.generator');

        if (! class_exists($generatorClassName)) {
            throw new Exception("Configured OTP generator class [{$generatorClassName}] does not exist.");
        }

        $generatorInstance = new $generatorClassName;
        if (! $generatorInstance instanceof OneTimePasscodeGenerator) {
            throw new RuntimeException("Configured OTP generator [{$generatorClassName}] must extend ".OneTimePasscodeGenerator::class);
        }

        return $generatorInstance;
    }
}
