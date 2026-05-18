<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;
use App\Http\Resources\ActivityLogResource;

class ActivityLogController extends Controller
{
    //
    public function index(Request $request)
    {
        $collection = ActivityLogResource::collection(
            Activity::with('causer')
                ->where('causer_id', auth()->id())
                ->where('causer_type', User::class)
                ->when($request->log_name, fn($q) => $q->where('log_name', $request->log_name))
                ->when($request->event, fn($q) => $q->where('event', $request->event))
                ->latest()
                ->orderBy('id', 'desc')
                ->paginate($request->get('per_page', 50))
        );

        return $this->data($collection);
    }
}
