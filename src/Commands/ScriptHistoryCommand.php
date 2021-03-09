<?php

namespace Narcisonunez\LaravelScripts\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Narcisonunez\LaravelScripts\Models\ScriptRun;

class ScriptHistoryCommand extends Command
{
    public $signature = 'scripts:history {--script= : Show the history for this specific script} {--limit= : Number of rows returned. By default is 10}';

    public $description = 'Print the history of scripts runs';

    public function handle()
    {
        $scriptName = $this->option('script');
        $limit = $this->option('limit') ?: 10;

        if ($scriptName && ! class_exists(config('scripts.base_path') . "\\$scriptName")) {
            $this->error('Class not found: ' . config('scripts.base_path') . "\\" . $scriptName);
            $options = $this->showScriptOptions();
            $scriptName = is_array($options) ? $options[0] : $options;
        }

        $historyQuery = ScriptRun::query()
            ->orderByDesc('id')
            ->limit($limit);

        if ($scriptName) {
            $historyQuery->where('script_name', 'LIKE', "%$scriptName%");
        }

        $history = $historyQuery->get(['id', 'script_name', 'status', 'message', 'runner_ip']);
        $this->table(
            ['ID', 'Script Name', 'Status', 'Message', 'Runner IP'],
            $history->toArray()
        );
    }

    /**
     * Show available scripts to the user console
     * @return array|string
     */
    private function showScriptOptions()
    {
        $files = collect(File::allFiles(app_path('Scripts')))->map(function ($file) {
            return [
                'filename' => Str::replaceFirst('.php', '',  $file->getFilename()),
            ];
        })->values()->flatten()->toArray();
        $this->newLine();
        $this->info('Available commands');

        return $this->choice(
            'Pick one of the following commands. (Cmd + C to exit)',
            $files
        );
    }
}
