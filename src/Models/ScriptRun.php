<?php


namespace Narcisonunez\LaravelScripts\Models;


use Illuminate\Database\Eloquent\Model;

class ScriptRun extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::created(function(ScriptRun $scriptRun){
            $scriptRun->runner_ip = request()->ip();
            $scriptRun->save();
        });
    }

    public function succeeded(): bool
    {
        return $this->status == 'succeeded';
    }

    public function failed(): bool
    {
        return !$this->succeeded();
    }
}
