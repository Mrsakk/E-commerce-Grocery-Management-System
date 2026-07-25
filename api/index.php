<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

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
if (!file_exists($sqlitePath)) {
    @touch($sqlitePath);
}

// Register Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Auto-run migrations & seeders on Vercel if SQLite database is fresh/missing tables
try {
    if (!Schema::hasTable('users') || !Schema::hasTable('banners')) {
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
    }
} catch (\Throwable $e) {
    try {
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
    } catch (\Throwable $ex) {
        error_log('Vercel Migration Exception: ' . $ex->getMessage());
    }
}

// Handle the HTTP request
$app->handleRequest(Request::capture());
