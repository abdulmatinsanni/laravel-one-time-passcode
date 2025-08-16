<?php

namespace AbdulmatinSanni\LaravelOneTimePasscode\Contracts;

use AbdulmatinSanni\LaravelOneTimePasscode\Models\OneTimePasscode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface CanUseOneTimePasscodes
{
    /**
     * Get the value of the model's primary key.
     */
    public function getKey(): mixed;

    /**
     * Get the class name for polymorphic relations.
     */
    public function getMorphClass(): string;

    /**
     * Define a polymorphic one-to-many relationship with OneTimePasscode.
     *
     * @return MorphMany<OneTimePasscode, Model>
     */
    public function oneTimePasscodes(): MorphMany;
}
