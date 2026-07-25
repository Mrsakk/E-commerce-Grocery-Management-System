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

// Forward Vercel requests to public/index.php
require __DIR__ . '/../public/index.php';
