<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityLogResource;
use App\Traits\HasProjectContext;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    use HasProjectContext;

    public function index(Request $request)
    {
        $projectId = $this->currentProjectId();

        $collection = ActivityLogResource::collection(
            Activity::with('causer')
                ->where('project_id', $projectId)
                ->when($request->log_name, fn($q) => $q->where('log_name', $request->log_name))
                ->when($request->event, fn($q) => $q->where('event', $request->event))
                ->when($request->causer_id, fn($q) => $q->where('causer_id', $request->causer_id))
                ->latest()
                ->orderBy('id', 'desc')
                ->paginate($request->get('per_page', 50))
        );

        return $this->data($collection);
    }
}
