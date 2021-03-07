<?php

namespace Narcisonunez\LaravelScripts\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Narcisonunez\LaravelScripts\Models\ScriptRun;
use Narcisonunez\LaravelScripts\Scripts\VerifyScriptRunScript;

class ScriptRunFactory extends Factory
{
    protected $model = ScriptRun::class;

    public function definition()
    {
        return [
            'script_name' => VerifyScriptRunScript::class,
            'description' => $this->faker->sentence,
            'status' => 'succeeded',
            'message' => '',
            'runner_ip' => $this->faker->ipv4,
            'executed_queries' => []
        ];
    }
}
