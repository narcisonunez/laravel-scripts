<?php


namespace Narcisonunez\LaravelScripts\Tests\Http\Controllers;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Narcisonunez\LaravelScripts\Database\Factories\ScriptRunFactory;
use Narcisonunez\LaravelScripts\Models\ScriptRun;
use Narcisonunez\LaravelScripts\Tests\TestCase;

class ScriptRunsControllerTest extends TestCase
{
    use WithoutMiddleware;

    /** @test */
    public function it_should_load_the_history_page()
    {
        /** @var ScriptRun $scriptRun */
        $scriptRun = ScriptRunFactory::new()->create();
        $this->get('/scripts')
            ->assertSeeText($scriptRun->script_name)
            ->assertSeeText($scriptRun->status)
            ->assertSeeText($scriptRun->description)
            ->assertOk();
    }
}
