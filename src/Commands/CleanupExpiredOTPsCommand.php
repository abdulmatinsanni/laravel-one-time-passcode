<?php

namespace AbdulmatinSanni\LaravelOneTimePasscode\Commands;

use AbdulmatinSanni\LaravelOneTimePasscode\Models\OneTimePasscode;
use Illuminate\Console\Command;

class CleanupExpiredOTPsCommand extends Command
{
    public $signature = 'one-time-passcode:cleanup-expired-otps';

    public $description = 'Cleanup expired one-time passcodes';

    public function handle(OneTimePasscode $oneTimePasscode): int
    {
        $deletedExpiredOtpsCount = $oneTimePasscode->expired()->delete();

        $this->info("🗑️  Deleted {$deletedExpiredOtpsCount} expired OTP(s).");

        return self::SUCCESS;
    }
}
