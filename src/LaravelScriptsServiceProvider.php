<?php

namespace Narcisonunez\LaravelScripts;

use Narcisonunez\LaravelScripts\Commands\ScriptHistoryCommand;
use Narcisonunez\LaravelScripts\Commands\ScriptMakeCommand;
use Narcisonunez\LaravelScripts\Commands\ScriptRunCommand;
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
            ->hasMigration('create_script_runs_table')
            ->hasCommands([
                ScriptMakeCommand::class,
                ScriptHistoryCommand::class,
                ScriptRunCommand::class,
            ]);

        $this->loadViewsFrom($this->package->basePath('/../resources/views'), 'scripts');

        if (method_exists($package, 'hasRoute')) {
            $package->hasRoute('web');
        } else {
            $this->loadRoutesFrom("{$this->package->basePath('/../routes/')}web.php");
        }
    }
}
