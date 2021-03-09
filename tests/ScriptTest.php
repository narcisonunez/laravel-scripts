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

        $scriptMock = $this->getMockBuilder(VerifyScriptRunScript::class)
            ->onlyMethods(['success'])
            ->enableProxyingToOriginalMethods()
            ->getMock();
        $scriptMock->runAsTransaction = true;
        $scriptMock->expects($this->once())->method('success');
        $scriptMock->execute();
    }

    /** @test */
    public function it_should_not_run_inside_a_transaction_when_property_is_set_to_false()
    {
        DB::partialMock()->shouldReceive('enableQueryLog')->once();
        DB::partialMock()->shouldReceive('beginTransaction')->never();
        DB::partialMock()->shouldReceive('getQueryLog')->once();
        DB::partialMock()->shouldReceive('disableQueryLog')->once();
        DB::partialMock()->shouldReceive('commit')->never();

        $scriptMock = $this->getMockBuilder(VerifyScriptRunScript::class)
            ->onlyMethods(['success'])
            ->enableProxyingToOriginalMethods()
            ->getMock();

        $scriptMock->expects($this->once())->method('success');
        $scriptMock->execute();
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
            ->onlyMethods(['run', 'fails'])
            ->enableProxyingToOriginalMethods()
            ->getMock();

        $scriptMock->runAsTransaction = true;
        $scriptMock->expects($this->once())->method('fails');

        $scriptMock->method('run')->willThrowException(new \Exception());
        $scriptMock->execute();
    }
}
