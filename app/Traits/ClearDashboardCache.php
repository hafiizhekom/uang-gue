<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
trait ClearDashboardCache
{
    protected function refreshDashboard($userId, $periodId = null)
    {
        if (!$userId) return;

        if ($periodId) {
            // Hapus spesifik 1 periode (Dipakai di Income/Outcome Observer nanti)
            Cache::forget("dashboard_user_{$userId}_period_{$periodId}");
        } else {
            // Hapus SEMUA periode (Dipakai di Master Data Observer)
            // Kita cari semua key yang depannya "dashboard_user_{id}_period_"
            $prefix = config('cache.prefix');
            $pattern = "{$prefix}:dashboard_user_{$userId}_period_*";
            
            $keys = Redis::keys($pattern);

            foreach ($keys as $key) {
                // Redis::keys balikin full name, kita bersihin prefixnya buat Cache::forget
                $actualKey = str_replace("{$prefix}:", "", $key);
                Cache::forget($actualKey);
            }
        }
    }
}