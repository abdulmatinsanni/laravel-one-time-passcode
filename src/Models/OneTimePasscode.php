<?php

namespace AbdulmatinSanni\LaravelOneTimePasscode\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Crypt;

/**
 * @property int $id
 * @property string $verifiable_id
 * @property string $verifiable_type
 * @property string $token
 * @property string $purpose
 * @property Carbon $expires_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method Builder expired()
 */
class OneTimePasscode extends Model
{
    use Prunable;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Encrypts and decrypts token during persistence and retrieval respectively.
     */
    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Crypt::decryptString($value),
            set: fn (string $value) => Crypt::encryptString($value),
        );
    }

    /**
     * Returns the verifiable entity of this OTP
     *
     * @return MorphTo<Model, $this>
     */
    public function verifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Returns OTP expiry status
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isBefore(Carbon::now());
    }

    /**
     * Scope a query to only expired tokens.
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        return static::query()->tap(fn ($q) => $this->scopeExpired($q));
    }
}
