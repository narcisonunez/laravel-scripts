<?php


namespace Narcisonunez\LaravelScripts\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Narcisonunez\LaravelScripts\Models\ScriptRun;
use Narcisonunez\LaravelScripts\Script;
use Narcisonunez\LaravelScripts\Services\ScriptDependencyInput;

class ScriptRunsController
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function index(Request $request)
    {
        $scripts = $this->getScripts();
        $scriptRunsQuery = ScriptRun::orderByDesc('id');

        if ($request->has('name')) {
            $name = $request->get('name');
            $scriptRunsQuery->where('script_name', 'LIKE', "%$name%");
        }
        $scriptRuns = $scriptRunsQuery->paginate(10);

        return view('scripts::history', compact('scriptRuns', 'scripts'));
    }

    /**
     * @param ScriptRun $scriptRun
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function show($id)
    {
        $scriptRun = ScriptRun::find($id);
        $scripts = $this->getScripts();

        return view('scripts::show', compact('scripts', 'scriptRun'));
    }

    public function run(Request $request)
    {
        $scriptName = $request->get('script');
        $script = config('scripts.base_path') . "\\$scriptName";
        if (! class_exists($script)) {
            return response()->json([
                'error' => 'Class Not Found',
            ]);
        }

        try {
            /** @var Script $script */
            $script = new $script();
            $script->setDependencies(
                $this->getDependencies($request->except(['_token', 'script']))
            );
            $script->execute();
        } catch (\Exception $exception) {
        }

        return redirect()->route("scripts::show", [
            'id' => ScriptRun::where('script_name', 'LIKE', "%$scriptName%")->get()->reverse()->first(),
        ]);
    }

    public function getDependencies($inputs)
    {
        $dependencies = [];
        foreach ($inputs as $key => $value) {
            $dependencies[$key] = $value;
        }

        return $dependencies;
    }

    public function dependencies($id)
    {
        $script = config('scripts.base_path') . "\\$id";
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
            $dependencies[$dependencyInput->key()]['name'] = $dependencyInput->name;
            $dependencies[$dependencyInput->key()]['label'] = $dependencyInput->label();
            $dependencies[$dependencyInput->key()]['description'] = $dependencyInput->description;
            $dependencies[$dependencyInput->key()]['is_optional'] = $dependencyInput->isOptional;
        }

        return response()->json([
            'dependencies' => $dependencies,
        ]);
    }

    private function getScripts() : Collection
    {
        $scripts = collect();
        if (File::isDirectory(app_path('Scripts'))) {
            $scripts = collect(File::allFiles(app_path('Scripts')))->map(function ($file) {
                return [
                    'name' => Str::replaceFirst('.php', '',  $file->getFilename()),
                ];
            });
        }

        return $scripts;
    }
}
