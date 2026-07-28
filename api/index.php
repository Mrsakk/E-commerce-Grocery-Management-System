<?php

$tmpDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/testing',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($tmpDirs as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', '/tmp/storage/logs/php-error.log');

try {
    require __DIR__.'/../public/index.php';
} catch (Throwable $e) {
    http_response_code(500);
    if (env('APP_DEBUG') === 'true' || getenv('APP_DEBUG') === 'true') {
        echo '<h1>500 - Server Error</h1>';
        echo '<pre>'.htmlspecialchars($e->getMessage()."\n".$e->getTraceAsString()).'</pre>';
    } else {
        echo '<h1>500 - Server Error</h1><p>Please check Vercel function logs for details.</p>';
    }
}
