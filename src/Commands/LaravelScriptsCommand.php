<?php

namespace Narcisonunez\LaravelScripts\Commands;

use Illuminate\Console\Command;

class LaravelScriptsCommand extends Command
{
    public $signature = 'scripts';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
