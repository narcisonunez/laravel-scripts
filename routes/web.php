<?php

use Illuminate\Support\Facades\Route;
use Narcisonunez\LaravelScripts\Http\Controllers\ScriptRunsController;

Route::macro('laravelScripts', function($prefix){
    Route::prefix($prefix)->group(function(){
        Route::get('/', [ScriptRunsController::class, 'index'])->name('scripts::history');
        Route::get('/run/{id}', [ScriptRunsController::class, 'show'])->name('scripts::show');
        Route::post('/run/{id}', [ScriptRunsController::class, 'run'])->name('scripts::run');
        Route::get('/dependencies/{id}', [ScriptRunsController::class, 'dependencies'])->name('scripts::dependencies');
    });
});
