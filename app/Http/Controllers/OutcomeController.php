<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexOutcomeRequest;
use App\Http\Requests\StoreUpdateOutcomeRequest;
use App\Http\Resources\OutcomeResource;
use App\Models\Outcome;
use App\Traits\HasProjectContext;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class OutcomeController extends Controller implements HasMiddleware
{
    use HasProjectContext;

    public static function middleware(): array
    {
        return [
            // Policy middleware for specific methods
            new Middleware('can:view,outcome', only: ['show']),
            new Middleware('can:update,outcome', only: ['update']),
            new Middleware('can:delete,outcome', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexOutcomeRequest $request)
    {
        $projectId = $this->currentProjectId();

        $outcomes = Outcome::query()
            ->where(function ($query) use ($projectId) {
                if ($projectId) {
                    $query->where('project_id', $projectId);
                } else {
                    $query->where('user_id', auth()->id());
                }
            })
            ->where('master_period_id', $request->master_period_id)
            ->with(['category', 'payment', 'type'])
            ->withCount('details')
            ->latest('date')
            ->get();

        return OutcomeResource::collection($outcomes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateOutcomeRequest $request)
    {
        $data = $request->validated();
        if (!isset($data['project_id']) && $this->currentProjectId()) {
            $data['project_id'] = $this->currentProjectId();
        }

        return $this->success(
            new OutcomeResource(Outcome::create($data)->load(['category', 'payment', 'type'])),
            'Outcome created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Outcome $outcome)
    {
        return $this->data(new OutcomeResource($outcome->load(['category', 'payment', 'type'])));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateOutcomeRequest $request, Outcome $outcome)
    {
        $outcome->update($request->validated());
        return $this->success(
            new OutcomeResource($outcome->load(['category', 'payment', 'type'])),
            'Outcome updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outcome $outcome)
    {
        $outcome->delete();
        return $this->success(null, 'Outcome deleted successfully');
    }
}
