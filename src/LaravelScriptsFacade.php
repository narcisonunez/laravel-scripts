<?php

namespace Narcisonunez\LaravelScripts;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Narcisonunez\LaravelScripts\LaravelScripts
 */
class LaravelScriptsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-scripts';
    }
}
