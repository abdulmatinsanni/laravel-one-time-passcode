<?php

namespace AbdulmatinSanni\LaravelOneTimePasscode\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AbdulmatinSanni\LaravelOneTimePasscode\LaravelOneTimePasscode
 */
class LaravelOneTimePasscode extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \AbdulmatinSanni\LaravelOneTimePasscode\LaravelOneTimePasscode::class;
    }
}
