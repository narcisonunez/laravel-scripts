<?php


namespace Narcisonunez\LaravelScripts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Narcisonunez\LaravelScripts\Models\ScriptRun;

class ScriptRunsController
{
    public function __invoke(Request $request)
    {
        $scripts = collect();
        if (File::isDirectory(app_path('Scripts'))) {
            $scripts = collect(File::allFiles(app_path('Scripts')))->map(function ($file) {
                return [
                    'name' => Str::replaceFirst('.php', '',  $file->getFilename()),
                ];
            });
        }

        $scriptRunsQuery = ScriptRun::orderByDesc('id');
        if ($request->has('name')) {
            $name = $request->get('name');
            $scriptRunsQuery->where('script_name', 'LIKE', "%$name%");
        }
        $scriptRuns = $scriptRunsQuery->paginate(10);

        return view('scripts::history', compact('scriptRuns', 'scripts'));
    }
}
