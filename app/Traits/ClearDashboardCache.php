<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait ClearDashboardCache
{
    protected function refreshDashboard($projectId)
    {
        if (!$projectId) return;

        $key = "dashboard_project_{$projectId}";
        
        Cache::forget($key);

        Log::info("Explicit Cache Forget: " . $key);
    }
}