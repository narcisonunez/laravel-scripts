<?php

namespace Narcisonunez\LaravelScripts\Tests\Commands;

use Illuminate\Support\Collection;
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
        $this->getTableRows(10);
        $rows = ScriptRunFactory::times(1)->create([
            'script_name' => 'Narcisonunez\LaravelScripts\Scripts\AnotherScript',
        ])->map(function (ScriptRun $scriptRun) {
            return [
                $scriptRun->id,
                $scriptRun->script_name,
                $scriptRun->status,
                $scriptRun->message ?: '',
                $scriptRun->runner_ip ?: '',
            ];
        })->toArray();

        $this->artisan('scripts:history', [
            '--script' => 'AnotherScript',
        ])
            ->expectsTable(
                ['ID', 'Script Name', 'Status', 'Message', 'Runner IP'],
                $rows
            )
            ->assertExitCode(0);
    }

    /** @test */
    public function it_should_print_not_found_class_error_for_unknown_scripts()
    {
        $this->getTableRows(10);

        $this->artisan('scripts:history', [
            '--script' => 'ScriptNameThatDoesntExist',
        ])
            ->expectsOutput('Class not found: ' . config('scripts.base_path') . "\\ScriptNameThatDoesntExist")
            ->assertExitCode(0);
    }
}
