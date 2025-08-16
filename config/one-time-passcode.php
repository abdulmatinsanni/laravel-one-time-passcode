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
    | OTP Expiry Time (seconds)
    |--------------------------------------------------------------------------
    |
    | The time-to-live (TTL) of an OTP in seconds before it expires.
    | Default: 300 (5 minutes).
    |
    */
    'otp_ttl' => 300,

    /*
    |--------------------------------------------------------------------------
    | Storage Driver
    |--------------------------------------------------------------------------
    |
    | Determines where OTPs are stored:
    | - "redis" → stored temporarily in Redis with expiry
    | - "database" → stored in the one_time_passcodes table
    | - "hybrid" → Redis for active OTP, DB for logging (recommended)
    |
    */
    'storage' => env('OTP_STORAGE_DRIVER', 'hybrid'),

    /*
    |--------------------------------------------------------------------------
    | Redis Key Prefix
    |--------------------------------------------------------------------------
    |
    | The prefix used when storing OTPs in Redis.
    | Example: otp:login:user:1
    |
    */
    'redis_prefix' => 'otp',

    /*
    |--------------------------------------------------------------------------
    | Max Attempts
    |--------------------------------------------------------------------------
    |
    | The maximum number of times an OTP can be attempted before being invalid.
    | Set to null for unlimited attempts (not recommended).
    |
    */
    'max_attempts' => 5,

    /*
    |--------------------------------------------------------------------------
    | Hashing Algorithm
    |--------------------------------------------------------------------------
    |
    | Hashing method used for OTPs. By default, Laravel's bcrypt (Hash::make)
    | is used. You can swap with argon2id if preferred.
    |
    | Options: "bcrypt", "argon2id"
    |
    */
    'hash_driver' => 'bcrypt',

    /*
    |--------------------------------------------------------------------------
    | Purge Expired
    |--------------------------------------------------------------------------
    |
    | Whether to automatically delete expired OTPs from the database.
    | For Redis, this is automatic since keys expire.
    |
    */
    'purge_expired' => true,

];
