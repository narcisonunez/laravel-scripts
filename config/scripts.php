<?php

return [
    /**
     * Table to save all the runs history
     */
    'table_name' => env('SCRIPTS_TABLE', 'script_runs'),

    /**
     * Base path where you put your scripts
     */
    'base_path' => 'App\\Scripts',

    /**
     * This value will be used to compare with the scripts' allowedRuns property
     * If the property has the same value as this key, we will allow the script
     * to run unlimited times
     */
    'unlimited_runs' => 0
];
