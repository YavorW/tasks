<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 *
 * @param Builder $eloquent
 * @return string sql
 */
function sqlDump(Builder $eloquent)
{
    $sql = $eloquent->toSql();
    $bindings = $eloquent->getBindings();
    foreach ($bindings as $replace) {
        $pos = strpos($sql, '?');
        if ($pos !== false) {
            $sql = substr_replace($sql, "'$replace'", $pos, 1);
        }
    }
    return $sql;
}

