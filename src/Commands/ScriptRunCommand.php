<?php

namespace Narcisonunez\LaravelScripts\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Narcisonunez\LaravelScripts\Script;
use Narcisonunez\LaravelScripts\Services\ScriptDependencyInput;

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

    /**
     * @param Script $script
     * @return array
     * @throws \Exception
     */
    private function getInteractiveValues(Script $script): array
    {
        $dependencies = [];
        foreach ($script->dependenciesValues as $dependency) {
            $dependencyInput = ScriptDependencyInput::for($dependency);

            $value = $this->askDependencyValue(
                $dependencyInput->label(),
                $dependencyInput->isOptional,
                $dependencyInput->description
            );

            $dependencies[$dependencyInput->key()] = $value;
            if (! $dependencyInput->isOptional && empty($value)) {
                throw new \Exception($dependencyInput->label() . " is a required dependency.");
            }
        }

        return $dependencies;
    }

    /**
     * @param $name
     * @param $isOptional
     * @param $description
     * @return mixed
     */
    private function askDependencyValue($name, $isOptional, $description = '') : mixed
    {
        $description = $description ?: '';
        return $this->ask($name . ": " . $description . ($isOptional ? ' (Optional)' : ''));
    }
}
