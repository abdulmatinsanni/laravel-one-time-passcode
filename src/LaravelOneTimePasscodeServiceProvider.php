<?php

namespace abdulmatinsanni\LaravelOneTimePasscode;

use abdulmatinsanni\LaravelOneTimePasscode\Commands\LaravelOneTimePasscodeCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelOneTimePasscodeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-one-time-passcode')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_one_time_passcode_table')
            ->hasCommand(LaravelOneTimePasscodeCommand::class);
    }
}
