<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OTP Length
    |--------------------------------------------------------------------------
    |
    | The number of digits/characters in the generated OTP.
    | Example: 6 → generates a 6-digit code.
    |
    */
    'length' => 6,

    /*
    |--------------------------------------------------------------------------
    | One-Time Passcode Generator
    |--------------------------------------------------------------------------
    |
    | Configure how OTP tokens are generated. You can swap the generator class
    | here. The class must extend
    | AbdulmatinSanni\LaravelOneTimePasscode\Contracts\OneTimePasscodeGenerator
    | abstract class.
    |
    */
    'generator' => \AbdulmatinSanni\LaravelOneTimePasscode\Support\PasscodeGenerators\NumericOneTimePasscodeGenerator::class,

    /*
    |--------------------------------------------------------------------------
    | OTP Expiry Time (seconds)
    |--------------------------------------------------------------------------
    |
    | The time-to-live (TTL) of an OTP in seconds before it expires.
    | Default: 300 (5 minutes).
    |
    */
    'ttl_seconds' => 300,

];
