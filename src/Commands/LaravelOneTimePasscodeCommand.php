<?php

namespace abdulmatinsanni\LaravelOneTimePasscode\Commands;

use Illuminate\Console\Command;

class LaravelOneTimePasscodeCommand extends Command
{
    public $signature = 'laravel-one-time-passcode';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
