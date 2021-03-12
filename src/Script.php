<?php

namespace Narcisonunez\LaravelScripts;

use Exception;
use Illuminate\Support\Facades\DB;
use Narcisonunez\LaravelScripts\Models\ScriptRun;
use stdClass;

abstract class Script
{
    /**
     * @var ScriptRun
     */
    private ScriptRun $scriptRun;

    /**
     * @var int
     */
    public int $allowedRuns = 0; // 0 = Unlimited

    /**
     * @var bool
     */
    public bool $runAsTransaction = false;

    /**
     * Script Description
     * @var string
     */
    public string $description = '';

    /**
     * @var array
     */
    public array $dependenciesValues = [];

    /**
     * Values need it by the script
     * @var stdClass
     */
    public stdClass $dependencies;

    /**
     * The method will be call when the script is run
     * @return void
     */
    abstract protected function run(): void;

    /**
     * The method will be call when the script is run successfully
     * @param ScriptRun $scriptRun
     * @return void
     */
    public function success(ScriptRun $scriptRun): void
    {
        //
    }

    /**
     * The method will be call when the script fails
     * @param ScriptRun $scriptRun
     * @return void
     */
    public function fails(ScriptRun $scriptRun): void
    {
        //
    }

    public function __construct()
    {
        $this->dependencies = new stdClass();
    }

    public function setDependencies(array $dependencies): self
    {
        foreach ($dependencies as $key => $value) {
            $this->dependencies->{$key} = $value;
        }

        return $this;
    }

    /**
     *
     * @throws Exception
     */
    public function execute()
    {
        if (
            $this->allowedRuns !== 0
            && !$this->canRun()
        ) {
            $exception = new Exception('This script reached the maximum allowed runs.');
            $this->setScriptRunAttributes($exception);
            $this->scriptRun->save();
            $this->fails($this->scriptRun);

            throw $exception;
        }

        DB::enableQueryLog();

        if ($this->runAsTransaction) {
            DB::beginTransaction();
        }

        try {
            $this->run();
            $this->setScriptRunAttributes();
            $this->scriptRun->save();

            $this->success($this->scriptRun->fresh());
        } catch (Exception $e) {
            $this->setScriptRunAttributes($e);
            $this->scriptRun->save();
            $this->fails($this->scriptRun->fresh());

            if ($this->runAsTransaction) {
                DB::rollBack();

                return;
            }
        }

        if ($this->runAsTransaction) {
            DB::commit();
        }

        DB::disableQueryLog();
    }

    /**
     * Set the scriptRun instance attributes
     * @param Exception|null $exception
     */
    private function setScriptRunAttributes(Exception $exception = null)
    {
        $this->scriptRun = new ScriptRun();
        $this->scriptRun->script_name = get_class($this);
        $this->scriptRun->description = $this->description;
        $this->scriptRun->message = 'Succeeded';
        $this->scriptRun->succeeded(true);
        $this->scriptRun->executed_queries = DB::getQueryLog();
        $this->scriptRun->dependencies = $this->dependencies;

        if ($exception) {
            $this->scriptRun->message = 'Failed';
            $this->scriptRun->failed(true);
            $this->scriptRun->message = $exception->getMessage();
        }
    }

    /**
     * @return bool
     */
    public function canRun(): bool
    {
        if ($this->allowedRuns === 0) return true;

        return ScriptRun::where('script_name', get_class($this))->count() < $this->allowedRuns;
    }
}
