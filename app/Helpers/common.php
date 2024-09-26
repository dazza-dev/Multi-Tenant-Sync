<?php

use App\Models\Project;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Set Database Connection
 */
function setDatabaseConn(string $connection, array $database, ?string $hash = null): void
{
    $prefix = 'database.connections.'.$connection.$hash;
    $host = $database['db_host'] ?? config('database.connections.cloud.host');
    $port = $database['db_port'] ?? '3306';

    config([$prefix.'.driver' => 'mysql']);
    config([$prefix.'.host' => $host]);
    config([$prefix.'.port' => $port]);
    config([$prefix.'.database' => $database['db_database']]);
    config([$prefix.'.username' => $database['db_username']]);
    config([$prefix.'.password' => $database['db_password']]);
    config([$prefix.'.charset' => 'utf8mb4']);
    config([$prefix.'.collation' => 'utf8mb4_unicode_ci']);
    config([$prefix.'.strict' => false]);
    config([$prefix.'.engine' => null]);
}

/**
 * Clear Database Connection
 */
function clearDatabaseConn(string $connection, ?string $hash = null): void
{
    $prefix = 'database.connections.'.$connection.$hash;

    config([$prefix.'.host' => '']);
    config([$prefix.'.port' => '']);
    config([$prefix.'.database' => '']);
    config([$prefix.'.username' => '']);
    config([$prefix.'.password' => '']);
}

/**
 * Set Project Connection
 */
function setProjectConn(Project $project): void
{
    setDatabaseConn('cloud', [
        'db_host' => $project->db_host,
        'db_port' => $project->db_port,
        'db_database' => $project->db_database,
        'db_username' => $project->db_username,
        'db_password' => $project->db_password,
    ]);
}

/**
 * Get Jobs Available
 */
function jobsAvailable()
{
    return collect(config('jobs-available'))->pluck('file')->all();
}

/**
 * Get Job File
 */
function getJobFile(string $job)
{
    $jobFile = config('jobs-available.'.$job.'.file');

    if (class_exists($jobFile)) {
        return $jobFile;
    } else {
        throw new \Exception("The job $jobFile does not exist.");
    }
}

/**
 * Get All Databases
 */
function getAllDatabases(string $connection, string $rawQuery, array $bindings = []): Collection
{
    $databases = DB::connection($connection)->select($rawQuery, $bindings);

    return collect($databases);
}

/**
 * Get Raw SQL Query
 */
function getRawSql(array $query): string
{
    $bindings = collect($query['bindings'])
        ->map(function ($binding) {
            return str_replace('\\', '\\\\', $binding);
        })->toArray();

    //
    $addSlashes = str_replace('?', "'?'", $query['query']);
    $rawSql = vsprintf(
        str_replace('?', '%s', $addSlashes),
        $bindings
    );

    return $rawSql;
}
