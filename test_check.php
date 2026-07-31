<?php

use App\Models\ActivityLog;
use App\Models\StockMovement;
use Illuminate\Contracts\Console\Kernel;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

echo 'StockMovement fillable: '.implode(', ', (new StockMovement)->getFillable()).PHP_EOL;
echo 'StockMovement table: '.(new StockMovement)->getTable().PHP_EOL;
echo 'ActivityLog fillable: '.implode(', ', (new ActivityLog)->getFillable()).PHP_EOL;
echo 'ActivityLog table: '.(new ActivityLog)->getTable().PHP_EOL;
