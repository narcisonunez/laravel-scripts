<?php


namespace Narcisonunez\LaravelScripts\Tests\Scripts;

use Narcisonunez\LaravelScripts\Models\ScriptRun;
use Narcisonunez\LaravelScripts\Scripts\VerifyScriptRunScript;
use Narcisonunez\LaravelScripts\Tests\TestCase;

class VerifyScriptRunScriptTest  extends TestCase
{
    /** @test */
    public function script_run_is_saved_when_an_script_runs()
    {
        (new VerifyScriptRunScript())->execute();

        $this->assertEquals(1, ScriptRun::get()->count());

        $scriptRunRecord = ScriptRun::get()->last();
        $this->assertEquals('succeeded', $scriptRunRecord->status);
        $this->assertEquals('Get all the script runs', $scriptRunRecord->description);
        $this->assertCount(1, $scriptRunRecord->executed_queries);
    }
}
