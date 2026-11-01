<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexOutcomeDetailRequest;
use App\Http\Requests\StoreUpdateOutcomeDetailRequest;
use App\Http\Resources\OutcomeDetailResource;
use App\Models\OutcomeDetail;
use App\Traits\HasProjectContext;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class OutcomeDetailController extends Controller
{
    use HasProjectContext;

    /**
     * Display a listing of the resource.
     */
    public function index(IndexOutcomeDetailRequest $request)
    {
        $projectId = $this->currentProjectId();

        return OutcomeDetailResource::collection(
            OutcomeDetail::query()
                ->where('outcome_id', $request->outcome_id)
                ->whereHas('outcome', function ($q) use ($projectId) {
                    if ($projectId) {
                        $q->where('project_id', $projectId);
                    } else {
                        $q->where('user_id', auth()->id());
                    }
                })
                ->with(['payment', 'tags'])
                ->latest('date')
                ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateOutcomeDetailRequest $request)
    {
        $data = $request->validated();
        $detail = OutcomeDetail::create(Arr::except($data, ['tags']));

        if (isset($data['tags'])) {
            $detail->tags()->sync($data['tags']);
        }

        return $this->success(
            new OutcomeDetailResource($detail->load(['payment', 'tags'])), 
            'Outcome detail created successfully', 
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(OutcomeDetail $outcomeDetail)
    {
        $projectId = $this->currentProjectId();
        $outcome = $outcomeDetail->outcome;

        if ($projectId ? !auth()->user()->belongsToProject($outcome->project_id) : $outcome->user_id !== auth()->id()) {
            return $this->error('You do not have access to this outcome detail.', 403);
        }

        return $this->data(new OutcomeDetailResource($outcomeDetail->load(['payment', 'tags'])));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateOutcomeDetailRequest $request, OutcomeDetail $outcomeDetail)
    {
        $projectId = $this->currentProjectId();
        $outcome = $outcomeDetail->outcome;

        if ($projectId ? !auth()->user()->belongsToProject($outcome->project_id) : $outcome->user_id !== auth()->id()) {
            return $this->error('You do not have access to this outcome detail.', 403);
        }

        $data = $request->validated();
        $outcomeDetail->update(Arr::except($data, ['tags']));

        if (isset($data['tags'])) {
            $outcomeDetail->tags()->sync($data['tags']);
        }

        return $this->success(
            new OutcomeDetailResource($outcomeDetail->load(['payment', 'tags'])),
            'Outcome detail updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OutcomeDetail $outcomeDetail)
    {
        $projectId = $this->currentProjectId();
        $outcome = $outcomeDetail->outcome;

        if ($projectId ? !auth()->user()->belongsToProject($outcome->project_id) : $outcome->user_id !== auth()->id()) {
            return $this->error('You do not have access to this outcome detail.', 403);
        }

        $outcomeDetail->tags()->detach();
        $outcomeDetail->delete();

        return $this->success(null, 'Outcome detail deleted successfully');
    }
}
