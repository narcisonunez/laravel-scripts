<?php

namespace Narcisonunez\LaravelScripts\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
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

    /**
     * @param Script $script
     * @return array
     * @throws \Exception
     */
    private function getInteractiveValues(Script $script): array
    {
        $dependencies = [];
        foreach ($script->dependenciesValues as $dependency) {
            $isOptional = Str::endsWith($dependency, '?');
            $value = $this->askDependencyValue($dependency, $isOptional);
            $dependency = $this->getDependencyKey($dependency);
            $dependencies[$dependency] = $value;

            if (! $isOptional && empty($value)) {
                throw new \Exception("$dependency is a required dependency.");
            }
        }

        return $dependencies;
    }

    /**
     * @param $value
     * @return string
     */
    private function getDependencyLabel($value) : string
    {
        $label = Str::title(implode(' ', preg_split('/(?=[A-Z])/', $value)));

        return Str::replaceLast('?', '', $label);
    }

    /**
     * @param $dependency
     * @return string
     */
    private function getDependencyKey($dependency) : string
    {
        return Str::replaceLast('?', '', $dependency);
    }

    /**
     * @param $dependency
     * @param $isOptional
     * @return mixed
     */
    private function askDependencyValue($dependency, $isOptional)
    {
        $name = $this->getDependencyLabel($dependency);

        return $this->ask($name . ': ' . ($isOptional ? ' (Optional)' : ''));
    }
}
