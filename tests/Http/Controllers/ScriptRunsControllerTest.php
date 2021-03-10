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
        config()->set('scripts.base_path', 'App\\Script');
        /** @var ScriptRun $scriptRun */
        $scriptRun = ScriptRunFactory::new()->create();
        $this->get('scripts')
            ->assertSeeText($scriptRun->script_name)
            ->assertSeeText($scriptRun->status)
            ->assertSeeText($scriptRun->description)
            ->assertOk();
    }
}
