<?php

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

// Forward Vercel requests to public/index.php
require __DIR__ . '/../public/index.php';
