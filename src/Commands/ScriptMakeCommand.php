<?php

namespace Narcisonunez\LaravelScripts\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ScriptMakeCommand extends GeneratorCommand
{
    public $signature = 'scripts:make {name} {--force}';

    public $description = 'Create a new Script Class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Script';

    public function handle()
    {
        if (parent::handle() === false && ! $this->option('force')) {
            return;
        }
    }

    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/script.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return config('scripts.base_path');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the script already exists'],
        ];
    }
}
