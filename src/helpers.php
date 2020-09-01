<?php

use Reedware\LaravelDumpToServer\Check;

if (! function_exists('isDumpServerOnline')) {

    /**
     * Returns whether or not the dump server is online.
     *
     * @return boolean
     */
    function isDumpServerOnline()
    {
        // Make sure the checker is bound
        if(!app()->bound(Check::class)) {
            return false;
        }

        // Return the check status
        return app(Check::class)->isOnline();
    }

}

if (! function_exists('dumpToServer')) {

    /**
     * Dumps to the dump server when it is online.
     *
     * @return void
     */
    function dumpToServer()
    {
        // Make sure the dump server is online
        if(!isDumpServerOnline()) {
            return;
        }

        // Dump to the dump server
        return dump(...func_get_args());
    }

}