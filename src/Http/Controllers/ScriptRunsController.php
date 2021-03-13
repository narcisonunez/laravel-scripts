<?php


namespace Narcisonunez\LaravelScripts\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Narcisonunez\LaravelScripts\Models\ScriptRun;

class ScriptRunsController
{
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): Factory|View|Application
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
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id): Factory|View|Application
    {
        $scriptRun = ScriptRun::find($id);
        $scripts = $this->getScripts();

        return view('scripts::show', compact('scripts', 'scriptRun'));
    }

    /**
     * @return Collection
     */
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
