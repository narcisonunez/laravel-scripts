<?php


namespace Narcisonunez\LaravelScripts\Tests\Http\Controllers;

use Narcisonunez\LaravelScripts\Database\Factories\ScriptRunFactory;
use Narcisonunez\LaravelScripts\Models\ScriptRun;
use Narcisonunez\LaravelScripts\Tests\TestCase;

class ScriptRunsControllerTest extends TestCase
{
    /** @test */
    public function it_should_load_the_history_page()
    {
        /** @var ScriptRun $scriptRun */
        $scriptRun = ScriptRunFactory::new()->create();
        $response = $this->get('scripts');
        $response->assertSeeText($scriptRun->script_name)
            ->assertSeeText($scriptRun->status)
            ->assertSeeText($scriptRun->description)
            ->assertOk();
    }

    /** @test */
    public function it_should_load_the_history_page_for_an_specific_script()
    {
        /** @var ScriptRun $verifyScript */
        $verifyScript = ScriptRunFactory::new()->create();
        /** @var ScriptRun $anotherRunScript */
        $anotherRunScript = ScriptRunFactory::new()->create(['script_name' => 'AnotherRunRecord']);
        $response = $this->call('GET', 'scripts', ['name' => 'VerifyScriptRunScript']);
        $response->assertSeeText($verifyScript->script_name)
            ->assertSeeText($verifyScript->status)
            ->assertSeeText($verifyScript->description)
            ->assertDontSeeText($anotherRunScript->script_name)
            ->assertDontSeeText($anotherRunScript->description)
            ->assertOk();
    }
}
