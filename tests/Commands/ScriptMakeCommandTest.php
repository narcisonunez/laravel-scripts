<?php

namespace Narcisonunez\LaravelScripts\Tests\Commands;

use Narcisonunez\LaravelScripts\Tests\TestCase;
use Illuminate\Support\Facades\File;

class ScriptMakeCommandTest extends TestCase
{
    /** @test */
    public function it_should_create_a_new_script_class_file()
    {
        config()->set('scripts.base_path', 'App\\Scripts');
        $filePath = app_path('Scripts') . '/AnotherScriptName.php';
        $this->assertFalse(File::exists($filePath));
        $this->artisan('scripts:make', [
            'name' => 'AnotherScriptName',
            '--force'
        ])
            ->expectsOutput('Script created successfully.')
            ->assertExitCode(0);

        $this->assertTrue(File::exists($filePath));
    }
}
