<?php

namespace Narcisonunez\LaravelScripts\Commands;

use Illuminate\Console\Command;
use Narcisonunez\LaravelScripts\Models\ScriptRun;
use Symfony\Component\Console\Input\InputOption;
use function GuzzleHttp\Psr7\try_fopen;

class ScriptRunCommand extends Command
{
    public $signature = 'scripts:run {name}';

    public $description = 'Run an specific script';

    public function handle()
    {
        $scriptName  = $this->argument('name');

        if ($scriptName && !class_exists(config('scripts.base_path') . "\\$scriptName")) {
            $this->error('Class not found: ' . config('scripts.base_path') . "\\" . $scriptName);
            return;
        }

        try {
            $scriptClass = config('scripts.base_path') . "\\$scriptName";
            (app()->get($scriptClass))->execute();
            $this->info('Your script ran successfully.');
        } catch (\Exception $exception) {
            $this->error('There was an error with your script.');
            $this->newLine(2);
            $this->error('Error: ' . $exception->getMessage());
        }

    }
}
