<?php

namespace Narcisonunez\LaravelScripts\Commands;

use Illuminate\Console\Command;
use Narcisonunez\LaravelScripts\Script;

class ScriptRunCommand extends Command
{
    public $signature = 'scripts:run {name} {--interactive}';

    public $description = 'Run an specific script';

    public function handle()
    {
        $scriptName = $this->argument('name');
        $isInteractive = $this->option('interactive');

        if ($scriptName && ! class_exists(config('scripts.base_path') . "\\$scriptName")) {
            $this->error('Class not found: ' . config('scripts.base_path') . "\\" . $scriptName);

            return;
        }

        try {
            $scriptClass = config('scripts.base_path') . "\\$scriptName";
            $scriptInstance = app()->get($scriptClass);
            if ($isInteractive) {
                $scriptInstance->setDependencies($this->getInteractiveValues($scriptInstance));
            }
            $scriptInstance->execute();
            $this->info('Your script ran successfully.');
        } catch (\Exception $exception) {
            $this->error('There was an error with your script.');
            $this->newLine(2);
            $this->error('Error: ' . $exception->getMessage());
        }
    }

    private function getInteractiveValues(Script $script): array
    {
        $dependencies = [];
        foreach ($script->dependenciesValues as $dependency) {
            $name = ucwords(implode(' ', preg_split('/(?=[A-Z])/', $dependency)));
            $dependencies[$dependency] = $this->ask($name . ': ');
        }

        return $dependencies;
    }
}
