<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class IdGenerator
{
    // Next AUTO_INCREMENT value of a table (never reuses deleted ids), padded: 5 -> "005"
    public static function next($table)
    {
        // MySQL 8 caches table stats; make it read the live value
        try {
            DB::statement('SET SESSION information_schema_stats_expiry = 0');
        } catch (\Throwable $e) {
            // MariaDB / older MySQL don't have this setting and don't need it
        }

        $next = DB::table('information_schema.TABLES')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->value('AUTO_INCREMENT') ?? 1;

        return str_pad($next, 3, '0', STR_PAD_LEFT);
    }
}
