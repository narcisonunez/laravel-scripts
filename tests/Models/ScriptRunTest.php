<?php


namespace Narcisonunez\LaravelScripts\Tests\Models;

use Narcisonunez\LaravelScripts\Database\Factories\ScriptRunFactory;
use Narcisonunez\LaravelScripts\Models\ScriptRun;
use Narcisonunez\LaravelScripts\Tests\TestCase;

class ScriptRunTest extends TestCase
{
    /** @test */
    public function it_should_returns_true_if_the_script_run_failed()
    {
        /** @var ScriptRun $script */
        $script = ScriptRunFactory::new()->create(['status' => 'failed']);
        $this->assertTrue($script->failed());
    }

    /** @test */
    public function get_executed_queries_should_returns_an_array()
    {
        /** @var ScriptRun $script */
        $script = ScriptRunFactory::new()->create();
        $this->assertIsArray($script->executed_queries);
    }
}
