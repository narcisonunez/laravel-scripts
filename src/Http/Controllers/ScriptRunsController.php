<?php


namespace Narcisonunez\LaravelScripts\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Narcisonunez\LaravelScripts\Models\ScriptRun;

class ScriptRunsController
{
    public function __invoke()
    {
        $scripts = collect(File::allFiles(app_path('Scripts')))->map(function ($file) {
            return [
                'name' => Str::replaceFirst('.php', '',  $file->getFilename()),
            ];
        });
        $scriptRuns = ScriptRun::orderByDesc('id')->paginate(10);

        return view('scripts::history', compact('scriptRuns', 'scripts'));
    }
}
