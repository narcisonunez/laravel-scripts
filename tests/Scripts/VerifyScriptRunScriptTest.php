<?php


namespace Narcisonunez\LaravelScripts\Tests\Scripts;

use Exception;
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
    }

    /** @test */
    public function script_run_status_should_be_succeeded_when_script_runs_successfully()
    {
        (new VerifyScriptRunScript())->execute();
        $this->assertEquals('succeeded', ScriptRun::get()->first()->status);
    }

    /** @test */
    public function script_run_should_should_throw_an_exception_if_allowedRuns_is_exceeded()
    {
        $this->expectException(Exception::class);
        $script = new VerifyScriptRunScript();
        $script->allowedRuns = 1;
        $script->execute();
        $script->execute();
    }

    /** @test */
    public function fails_method_is_called_when_script_runs_fails()
    {
        $scriptRunMock = $this->getMockBuilder(VerifyScriptRunScript::class)
            ->onlyMethods(['fails'])
            ->getMock();

        $scriptRunMock->allowedRuns = 1;
        $scriptRunMock->execute();

        $scriptRunMock->expects($this->once())->method('fails');
        $this->expectException(Exception::class);
        $scriptRunMock->execute();

        $scriptRun = ScriptRun::get()->last();
        $this->assertEquals('failed', $scriptRun->status);
        $this->assertTrue($scriptRun->failed());
    }

    /** @test */
    public function success_method_is_called_when_script_runs_successfully()
    {
        $scriptRunMock = $this->getMockBuilder(VerifyScriptRunScript::class)
            ->onlyMethods(['success'])
            ->getMock();

        $scriptRunMock->expects($this->once())->method('success');
        $scriptRunMock->execute();

        $scriptRun = ScriptRun::get()->last();
        $this->assertEquals('succeeded', $scriptRun->status);
        $this->assertTrue($scriptRun->succeeded());
    }
}
