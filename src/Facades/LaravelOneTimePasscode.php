<?php

namespace abdulmatinsanni\LaravelOneTimePasscode\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \abdulmatinsanni\LaravelOneTimePasscode\LaravelOneTimePasscode
 */
class LaravelOneTimePasscode extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \abdulmatinsanni\LaravelOneTimePasscode\LaravelOneTimePasscode::class;
    }
}
