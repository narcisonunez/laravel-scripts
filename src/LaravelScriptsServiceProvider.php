<?php

namespace Narcisonunez\LaravelScripts;

use Narcisonunez\LaravelScripts\Commands\LaravelScriptsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelScriptsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-scripts')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_script_runs_table')
            ->hasCommand(LaravelScriptsCommand::class);
    }
}
