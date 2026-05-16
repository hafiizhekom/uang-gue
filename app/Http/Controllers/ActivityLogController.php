<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class ActivityLogController extends Controller
{
    //
    public function index(Request $request)
    {
        $logs = Activity::with('causer')
            ->where('causer_id', auth()->id())
            ->where('causer_type', User::class)
            ->when($request->log_name, fn($q) => $q->where('log_name', $request->log_name)) // filter by income/expense/dll
            ->when($request->event, fn($q) => $q->where('event', $request->event))           // filter by created/updated/deleted
            ->latest()
            ->paginate($request->get('per_page', 50));

        return response()->json([
            'status' => 'success',
            'data'   => $logs,
        ]);
    }
}
