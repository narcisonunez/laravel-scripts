<?php

use Illuminate\Support\Facades\Route;
use Narcisonunez\LaravelScripts\Http\Controllers\ScriptRunsController;

Route::macro('laravelScripts', function($prefix){
    Route::prefix($prefix)->group(function(){
        Route::get('/', ScriptRunsController::class);
    });
});
