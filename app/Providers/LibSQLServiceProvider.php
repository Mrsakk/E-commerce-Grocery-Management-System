<?php

namespace App\Providers;

use App\Database\Connectors\LibSQLConnector;
use Illuminate\Database\Connection;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\ServiceProvider;

class LibSQLServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('db.connector.libsql', fn () => new LibSQLConnector);

        Connection::resolverFor('libsql', function ($connection, $database, $prefix, $config) {
            return new class($connection, $database, $prefix, $config) extends SQLiteConnection
            {
                public function getDriverTitle(): string
                {
                    return 'LibSQL (Turso)';
                }
            };
        });
    }
}
