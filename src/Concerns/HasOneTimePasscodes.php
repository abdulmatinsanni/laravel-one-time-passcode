<?php

namespace AbdulmatinSanni\LaravelOneTimePasscode\Concerns;

use AbdulmatinSanni\LaravelOneTimePasscode\Contracts\CanUseOneTimePasscodes;
use AbdulmatinSanni\LaravelOneTimePasscode\LaravelOneTimePasscode;
use AbdulmatinSanni\LaravelOneTimePasscode\Models\OneTimePasscode;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
trait HasOneTimePasscodes
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
        if (! $this instanceof CanUseOneTimePasscodes) {
            throw new Exception('HasOneTimePasscodes can only be used on Eloquent models.');
        }

        return LaravelOneTimePasscode::create($this, $purpose, $otpExpiresInSeconds);
    }

    /**
     * Validate a given one-time passcode token.
     *
     * @throws Exception
     */
    public function validateOneTimePasscode(string $token, ?string $purpose = null): bool
    {
        if (! $this instanceof CanUseOneTimePasscodes) {
            throw new Exception('HasOneTimePasscodes can only be used on Eloquent models.');
        }

        LaravelOneTimePasscode::validate($this, $token, $purpose);

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
