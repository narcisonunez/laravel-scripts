<?php

namespace Narcisonunez\LaravelScripts\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Narcisonunez\LaravelScripts\LaravelScriptsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Narcisonunez\\LaravelScripts\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        config()->set('scripts.base_path', 'Narcisonunez\\LaravelScripts\\Scripts');
        if (Storage::exists(app_path('Scripts'))) {
            File::delete(File::allFiles(app_path('Scripts')));
        }

        Route::laravelScripts('scripts');
    }

    public function tearDown(): void
    {
        if (Storage::exists(app_path('Scripts'))) {
            File::delete(File::allFiles(app_path('Scripts')));
        }
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelScriptsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        include_once __DIR__.'/../database/migrations/create_script_runs_table.php.stub';
        (new \CreateScriptRunsTable())->up();
    }
}
