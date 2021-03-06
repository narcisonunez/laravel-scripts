<?php


namespace Narcisonunez\LaravelScripts\Scripts;

use Narcisonunez\LaravelScripts\Models\ScriptRun;
use Narcisonunez\LaravelScripts\Script;

class VerifyScriptRunScript extends Script
{
    /**
     * Script Description
     * @var string
     */
    protected string $description = 'Get all the script runs';

    /**
     * The method will be call when the script is run
     * @return void
     */
    protected function run() : void
    {
        ScriptRun::all();
    }
}
