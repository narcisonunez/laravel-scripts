<?php

namespace Narcisonunez\LaravelScripts\Commands;

use Illuminate\Console\Command;

class LaravelScriptsCommand extends Command
{
    public $signature = 'laravel-scripts';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
