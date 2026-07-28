<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ActivityLogger
{
    public static function log($action, $model, $description = null, $oldValues = null, $newValues = null)
    {
        try {
            $modelType = get_class($model);
            $modelId = $model->id ?? null;

            return ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model_type' => class_basename($modelType),
                'model_id' => $modelId,
                'description' => $description,
                'old_values' => $oldValues ? json_encode($oldValues) : null,
                'new_values' => $newValues ? json_encode($newValues) : null,
                'ip_address' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            Log::warning('ActivityLogger::log failed: '.$e->getMessage());

            return null;
        }
    }

    public static function logAction($action, $modelType, $modelId = null, $description = null)
    {
        try {
            return ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model_type' => $modelType,
                'model_id' => $modelId,
                'description' => $description,
                'ip_address' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            Log::warning('ActivityLogger::logAction failed: '.$e->getMessage());

            return null;
        }
    }
}
