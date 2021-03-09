<?php

namespace Narcisonunez\LaravelScripts\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ScriptMakeCommand extends GeneratorCommand
{
    public $signature = 'scripts:make {name : Name of the script} {--force : Replace if the class already exists}';

    public $description = 'Create a new Script Class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Script';

    /**
     * @return bool|void|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        parent::handle();
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
        return __DIR__.$stub;
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
}
