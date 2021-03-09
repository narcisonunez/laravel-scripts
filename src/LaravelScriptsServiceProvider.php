<?php

namespace Narcisonunez\LaravelScripts;

use Illuminate\Support\Facades\Route;
use Narcisonunez\LaravelScripts\Commands\ScriptHistoryCommand;
use Narcisonunez\LaravelScripts\Commands\ScriptMakeCommand;
use Narcisonunez\LaravelScripts\Commands\ScriptRunCommand;
use Narcisonunez\LaravelScripts\Http\Controllers\ScriptRunsController;
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
            ->hasRoute('web')
            ->hasViews()
            ->hasMigration('create_script_runs_table')
            ->hasCommands([
                ScriptMakeCommand::class,
                ScriptHistoryCommand::class,
                ScriptRunCommand::class,
            ]);
    }
}
