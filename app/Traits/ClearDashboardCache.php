<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
trait ClearDashboardCache
{
    protected function refreshDashboard($userId)
    {
        if (!$userId) return;

        $key = "dashboard_user_{$userId}";
        
        Cache::forget($key);

        Log::info("Explicit Cache Forget: " . $key);
    }
}