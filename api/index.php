<?php

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

define('LARAVEL_START', microtime(true));

// Create required writable storage directories in /tmp for Vercel's read-only environment
$directories = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/sessions',
    '/tmp/logs',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Ensure SQLite database file exists in /tmp
$sqlitePath = '/tmp/database.sqlite';
$isNewDb = !file_exists($sqlitePath) || filesize($sqlitePath) === 0;

if (!file_exists($sqlitePath)) {
    @touch($sqlitePath);
}

// Register Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel Application
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Auto-run migrations & seeders on Vercel when SQLite database is fresh/empty
if ($isNewDb) {
    try {
        /** @var ConsoleKernel $console */
        $console = $app->make(ConsoleKernel::class);
        $console->bootstrap();

        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
    } catch (\Throwable $e) {
        error_log('Vercel DB Migration Error: ' . $e->getMessage());
    }
}

// Handle the HTTP request
$app->handleRequest(Request::capture());
