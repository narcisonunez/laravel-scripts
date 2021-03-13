<?php

use Illuminate\Support\Facades\Route;
use Narcisonunez\LaravelScripts\Http\Controllers\ScriptRunsController;
use Narcisonunez\LaravelScripts\Http\Controllers\ScriptRunsActionsController;

Route::macro('laravelScripts', function($prefix){
    Route::prefix($prefix)->group(function(){
        Route::get('/', [ScriptRunsController::class, 'index'])->name('scripts::history');
        Route::get('/run/{id}', [ScriptRunsController::class, 'show'])->name('scripts::show');
        Route::post('/run/{id}', [ScriptRunsActionsController::class, 'run'])->name('scripts::run');
        Route::get('/dependencies/{id}', [ScriptRunsActionsController::class, 'dependenciesForScript'])->name('scripts::dependencies');
    });
});
