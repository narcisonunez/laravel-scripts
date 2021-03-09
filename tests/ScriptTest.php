<?php


namespace Narcisonunez\LaravelScripts\Tests;


use Illuminate\Support\Facades\DB;
use Narcisonunez\LaravelScripts\Scripts\VerifyScriptRunScript;

class ScriptTest extends TestCase
{
    /** @test */
    public function it_should_run_inside_a_transaction_when_property_is_set_to_true()
    {
        DB::partialMock()->shouldReceive('enableQueryLog')->once();
        DB::partialMock()->shouldReceive('beginTransaction')->once();
        DB::partialMock()->shouldReceive('getQueryLog')->once();
        DB::partialMock()->shouldReceive('disableQueryLog')->once();
        DB::partialMock()->shouldReceive('commit')->once();

        $script = new VerifyScriptRunScript();
        $script->runAsTransaction = true;
        $script->execute();
    }

    /** @test */
    public function it_should_not_run_inside_a_transaction_when_property_is_set_to_false()
    {
        DB::partialMock()->shouldReceive('enableQueryLog')->once();
        DB::partialMock()->shouldReceive('beginTransaction')->never();
        DB::partialMock()->shouldReceive('getQueryLog')->once();
        DB::partialMock()->shouldReceive('disableQueryLog')->once();
        DB::partialMock()->shouldReceive('commit')->never();

        $script = new VerifyScriptRunScript();
        $script->execute();
    }

    /** @test */
    public function it_should_catch_an_exception_when_run_method_throws_an_exception()
    {
        DB::partialMock()->shouldReceive('enableQueryLog')->once();
        DB::partialMock()->shouldReceive('beginTransaction')->once();
        DB::partialMock()->shouldReceive('getQueryLog')->once();
        DB::partialMock()->shouldReceive('disableQueryLog')->never();
        DB::partialMock()->shouldReceive('rollBack')->once();
        DB::partialMock()->shouldReceive('commit')->never();

        $scriptMock = $this->getMockBuilder(VerifyScriptRunScript::class)
            ->onlyMethods(['run'])
            ->enableProxyingToOriginalMethods()
            ->getMock();

        $scriptMock->runAsTransaction = true;

        $scriptMock->method('run')->willThrowException(new \Exception());
        $scriptMock->execute();
    }
}
