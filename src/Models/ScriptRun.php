<?php


namespace Narcisonunez\LaravelScripts\Models;

use Illuminate\Database\Eloquent\Model;

class ScriptRun extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::created(function (ScriptRun $scriptRun) {
            $scriptRun->runner_ip = request()->ip();
            $scriptRun->save();
        });
    }

    /**
     * Get executed_queries attribute
     */
    public function getExecutedQueriesAttribute()
    {
        return json_decode($this->attributes['executed_queries']);
    }

    /**
     * Set executed_queries attribute
     * @param $value
     */
    public function setExecutedQueriesAttribute($value)
    {
        $this->attributes['executed_queries'] = json_encode($value);
    }

    /**
     * @param bool|null $status
     */
    public function succeeded(bool $status = null): bool
    {
        if ($status) {
            $this->status = 'succeeded';
            return true;
        }
        return $this->status == 'succeeded';
    }

    /**
     * @param bool|null $status
     * @return bool
     */
    public function failed(bool $status = null): bool
    {
        if ($status) {
            $this->status = 'failed';
            return true;
        }

        return ! $this->succeeded();
    }
}
