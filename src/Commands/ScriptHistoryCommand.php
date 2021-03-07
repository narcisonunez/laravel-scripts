<?php

namespace Narcisonunez\LaravelScripts\Commands;

use Illuminate\Console\Command;
use Narcisonunez\LaravelScripts\Models\ScriptRun;
use Symfony\Component\Console\Input\InputOption;

class ScriptHistoryCommand extends Command
{
    public $signature = 'scripts:history {--script} {--limit}';

    public $description = 'Print the history of scripts runs';

    public function handle()
    {
        $scriptName  = $this->option('script');
        $limit  = $this->option('limit') ?: 10;

        if ($scriptName && !class_exists(config('scripts.base_path') . "\\$scriptName")) {
            $this->error('Class not found: ' . config('scripts.base_path') . "\\" . $scriptName);
            return;
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
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['script', null, InputOption::VALUE_OPTIONAL, 'Show the history for this specific script'],
            ['limit', null, InputOption::VALUE_OPTIONAL, 'Number of rows returned. By default is 10.'],
        ];
    }
}
