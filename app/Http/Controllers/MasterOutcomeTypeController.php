<?php

namespace App\Http\Controllers;

use App\Http\Requests\Master\StoreUpdateOutcomeTypeRequest;
use App\Http\Resources\MasterResource;
use App\Models\MasterOutcomeType;
use App\Traits\HasProjectContext;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MasterOutcomeTypeController extends Controller implements HasMiddleware
{
    use HasProjectContext;

    public static function middleware(): array
    {
        return [
            new Middleware('can:view,master_outcome_type', only: ['show']),
            new Middleware('can:update,master_outcome_type', only: ['update']),
            new Middleware('can:delete,master_outcome_type', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projectId = $this->currentProjectId();

        $collection = MasterResource::collection(
            MasterOutcomeType::query()
                ->where(function ($query) use ($projectId) {
                    if ($projectId) {
                        $query->where('project_id', $projectId);
                    } else {
                        $query->where('user_id', auth()->id());
                    }
                })
                ->withCount('outcomes')
                ->orderBy('id', 'asc')
                ->cursorPaginate(50)
        );

        return $this->data($collection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateOutcomeTypeRequest $request)
    {
        $data = $request->validated();
        if (!isset($data['project_id']) && $this->currentProjectId()) {
            $data['project_id'] = $this->currentProjectId();
        }

        $outcomeType = MasterOutcomeType::create($data);
        return $this->success(new MasterResource($outcomeType), 'Outcome Type created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterOutcomeType $masterOutcomeType)
    {
        return $this->data(new MasterResource($masterOutcomeType));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateOutcomeTypeRequest $request, MasterOutcomeType $masterOutcomeType)
    {
        $masterOutcomeType->update($request->validated());
        return $this->success(new MasterResource($masterOutcomeType), 'Outcome Type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterOutcomeType $masterOutcomeType)
    {
        $masterOutcomeType->delete();
        return $this->success(null, 'Outcome Type deleted successfully');
    }
}
