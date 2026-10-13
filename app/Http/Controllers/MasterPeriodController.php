<?php

namespace App\Http\Controllers;

use App\Http\Requests\Master\StoreUpdatePeriodRequest;
use App\Http\Resources\MasterResource;
use App\Models\MasterPeriod;
use App\Traits\HasProjectContext;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Controllers\Controller;

class MasterPeriodController extends Controller implements HasMiddleware
{
    use HasProjectContext;

    public static function middleware(): array
    {
        return [
            // Pake Policy buat method tertentu
            new Middleware('can:view,master_period', only: ['show']),
            new Middleware('can:update,master_period', only: ['update']),
            new Middleware('can:delete,master_period', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projectId = $this->currentProjectId();

        $collection = MasterResource::collection(
            MasterPeriod::query()
                ->where(function ($query) use ($projectId) {
                    if ($projectId) {
                        $query->where('project_id', $projectId);
                    } else {
                        $query->where('user_id', auth()->id());
                    }
                })
                ->withCount(['outcomes', 'incomes'])
                ->orderBy('id', 'asc')
                ->cursorPaginate(50)
        );

        return $this->data($collection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdatePeriodRequest $request)
    {
        $data = $request->validated();
        if (!isset($data['project_id']) && $this->currentProjectId()) {
            $data['project_id'] = $this->currentProjectId();
        }

        $period = MasterPeriod::create($data);
        return $this->success(new MasterResource($period), 'Period created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterPeriod $masterPeriod)
    {
        return $this->data(new MasterResource($masterPeriod));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdatePeriodRequest $request, MasterPeriod $masterPeriod)
    {
        $masterPeriod->update($request->validated());
        return $this->success(new MasterResource($masterPeriod), 'Period updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterPeriod $masterPeriod)
    {
        $masterPeriod->delete();
        return $this->success(null, 'Period deleted successfully');
    }
}
