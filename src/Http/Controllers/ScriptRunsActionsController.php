<?php


namespace Narcisonunez\LaravelScripts\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Narcisonunez\LaravelScripts\Models\ScriptRun;
use Narcisonunez\LaravelScripts\Script;
use Narcisonunez\LaravelScripts\Services\ScriptDependencyInput;

class ScriptRunsActionsController
{
    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function run(Request $request): JsonResponse|RedirectResponse
    {
        $scriptName = $request->get('script');
        $script = "App\\Scripts\\$scriptName";
        if (! class_exists($script)) {
            return response()->json([
                'error' => 'Class Not Found',
            ]);
        }

        /** @var Script $script */
        $script = new $script();
        if (! $script->canRun()) {
            session()->flash('scripts::cannot_run', 'This script reached the maximum allowed runs.');
            return redirect()->route("scripts::history");
        }

        try {
            $script->setDependencies(
                $this->getDependenciesFromRequest($request)
            );
            $script->execute();
        } catch (\Exception $exception) {
        }

        return redirect()->route("scripts::show", [
            'id' => ScriptRun::where('script_name', 'LIKE', "%$scriptName%")->get()->reverse()->first(),
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getDependenciesFromRequest(Request $request): array
    {
        $dependencies = [];
        foreach ($request->except(['_token', 'script']) as $key => $value) {
            $dependencies[$key] = $value;
        }

        return $dependencies;
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function dependenciesForScript($id): JsonResponse
    {
        $script = "App\\Scripts\\$id";
        if (! class_exists($script)) {
            return response()->json([
                'error' => 'Class Not Found',
            ]);
        }

        /** @var Script $script */
        $script = new $script();
        $dependencies = [];

        foreach ($script->dependenciesValues as $dependency) {
            $dependencyInput = ScriptDependencyInput::for($dependency);
            $dependencies[$dependencyInput->key()] = [
                'name' => $dependencyInput->name,
                'label' => $dependencyInput->label(),
                'description' => $dependencyInput->description,
                'is_optional' => $dependencyInput->isOptional,
            ];
        }

        return response()->json([
            'dependencies' => $dependencies,
        ]);
    }
}
