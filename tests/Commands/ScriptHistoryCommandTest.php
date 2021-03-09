<?php

namespace Narcisonunez\LaravelScripts\Tests\Commands;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Narcisonunez\LaravelScripts\Database\Factories\ScriptRunFactory;
use Narcisonunez\LaravelScripts\Models\ScriptRun;
use Narcisonunez\LaravelScripts\Tests\TestCase;

class ScriptHistoryCommandTest extends TestCase
{
    private function getTableRows(int $times = 1)
    {
        $scriptRuns = ScriptRunFactory::times($times)->create();

        return $scriptRuns->sortByDesc('id')->map(function ($scriptRun) {
            return [
                $scriptRun->id,
                $scriptRun->script_name,
                $scriptRun->status,
                $scriptRun->message ?: '',
                $scriptRun->runner_ip ?: '',
            ];
        });
    }

    /** @test */
    public function it_should_print_history_for_default_limit_of_script_runs()
    {
        /** @var Collection $rows */
        $rows = $this->getTableRows(10)->toArray();
        $this->artisan('scripts:history')
            ->expectsTable(
                ['ID', 'Script Name', 'Status', 'Message', 'Runner IP'],
                $rows
            )
            ->assertExitCode(0);
    }

    /** @test */
    public function it_should_print_history_limit_of_5_script_runs()
    {
        $rows = $this->getTableRows(10)->take(5)->toArray();

        $this->artisan('scripts:history', [
            '--limit' => 5,
        ])
            ->expectsTable(
                ['ID', 'Script Name', 'Status', 'Message', 'Runner IP'],
                $rows
            )
            ->assertExitCode(0);
    }

    /** @test */
    public function it_should_print_history_limit_of_1_script_runs_for_specific_script()
    {
        config()->set('scripts.base_path', 'App\\Scripts');
        $this->artisan('scripts:make', [
            'name' => 'AnotherScript',
            '--force',
        ]);

        $files = ['AnotherScript'];

        /** @var ScriptRun $scriptRun */
        $scriptRun = ScriptRunFactory::new()->create(['script_name' => 'NameSpace\\AnotherScript']);
        $this->artisan('scripts:history', [
            '--script' => 'BadNameScript',
        ])
            ->expectsOutput('Class not found: ' . config('scripts.base_path') . "\\BadNameScript")
            ->expectsChoice(
                'Pick one of the following commands. (Cmd + C to exit)',
                $files,
                [$files[0]]
            )
            ->expectsTable(
                ['ID', 'Script Name', 'Status', 'Message', 'Runner IP'],
                [
                    [
                        $scriptRun->id,
                        $scriptRun->script_name,
                        $scriptRun->status,
                        $scriptRun->message,
                        $scriptRun->runner_ip,
                    ],
                ]
            )
            ->assertExitCode(0);
    }

    /** @test */
    public function it_should_print_not_found_class_error_for_unknown_scripts()
    {
        if (File::isDirectory(app_path('Scripts'))){
            File::delete(app_path('Scripts'));
        }
        config()->set('scripts.base_path', 'App\\Scripts');
        $this->artisan('scripts:make', [
            'name' => 'VerifyUsersScript',
        ]);

        $rows = ScriptRunFactory::times(1)->create([
            'script_name' => app_path('Scripts') . '\\VerifyUsersScript',
        ])->map(function (ScriptRun $scriptRun) {
            return [
                $scriptRun->id,
                $scriptRun->script_name,
                $scriptRun->status,
                $scriptRun->message ?: '',
                $scriptRun->runner_ip ?: '',
            ];
        })->toArray();
        $files = collect(File::allFiles(app_path('Scripts')))->map(function ($file) {
            return [
                'filename' => Str::replaceFirst('.php', '',  $file->getFilename()),
            ];
        })->values()->flatten()->toArray();

        $this->artisan('scripts:history', [
            '--script' => 'ScriptNameThatDoesntExist',
        ])
            ->expectsOutput('Class not found: ' . config('scripts.base_path') . "\\ScriptNameThatDoesntExist")
            ->expectsChoice(
                'Pick one of the following commands. (Cmd + C to exit)',
                $files,
                [$files[0]]
            )
            ->expectsTable(
                ['ID', 'Script Name', 'Status', 'Message', 'Runner IP'],
                $rows
            )->assertExitCode(0);
    }
}
