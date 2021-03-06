<?php

namespace Narcisonunez\LaravelScripts;

use Exception;
use Illuminate\Support\Facades\DB;
use Narcisonunez\LaravelScripts\Models\ScriptRun;

abstract class Script
{
    /**
     * @var ScriptRun
     */
    private ScriptRun $scriptRun;

    /**
     * @var int
     */
    protected int $allowedRuns;

    /**
     * @var bool
     */
    protected bool $runAsTransaction = false;

    /**
     * Script Description
     * @var string
     */
    protected string $description = '';

    /**
     * The method will be call when the script is run
     * @return void
     */
    abstract protected function run() : void;

    /**
     * The method will be call when the script is run successfully
     * @param ScriptRun $scriptRun
     * @return void
     */
    protected function success(ScriptRun $scriptRun) : void
    {
        //
    }

    /**
     * The method will be call when the script fails
     * @param ScriptRun $scriptRun
     * @return void
     */
    protected function fails(ScriptRun $scriptRun) : void
    {
        //
    }

    public function __construct()
    {
        $this->allowedRuns = config('scripts.unlimited_runs');
    }

    /**
     *
     * @throws Exception
     */
    public function execute()
    {
        if ($this->allowedRuns != config('scripts.unlimited_runs') && ScriptRun::where(get_class($this)->count() >= $this->allowedRuns)) {
            throw new Exception('This script reached the maximum allowed runs.');
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
        } catch(Exception $e) {
            $this->setScriptRunAttributes($e);
            $this->scriptRun->save();
            $this->fails($this->scriptRun->fresh());

            if ($this->runAsTransaction) {
                DB::rollBack();
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
        $this->scriptRun->succeeded(true);
        $this->scriptRun->executed_queries = DB::getQueryLog();

        if ($exception) {
            $this->scriptRun->failed(true);
            $this->scriptRun->message = $exception->getMessage();
        }
    }
}
