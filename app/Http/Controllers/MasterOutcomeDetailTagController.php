<?php

namespace App\Http\Controllers;

use App\Http\Requests\Master\StoreUpdateOutcomeDetailTagRequest;
use App\Models\MasterOutcomeDetailTag;
use App\Http\Resources\MasterResource;
use App\Traits\HasProjectContext;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MasterOutcomeDetailTagController extends Controller implements HasMiddleware
{
    use HasProjectContext;

    public static function middleware(): array
    {
        return [
            new Middleware('can:view,master_outcome_detail_tag', only: ['show']),
            new Middleware('can:update,master_outcome_detail_tag', only: ['update']),
            new Middleware('can:delete,master_outcome_detail_tag', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projectId = $this->currentProjectId();

        $collection = MasterResource::collection(
            MasterOutcomeDetailTag::query()
                ->where(function ($query) use ($projectId) {
                    if ($projectId) {
                        $query->where('project_id', $projectId);
                    } else {
                        $query->where('user_id', auth()->id());
                    }
                })
                ->withCount(['outcome_details' => function ($query) {
                    $query->whereHas('outcome', function ($q) {
                        $q->whereNull('deleted_at');
                    });
                }])
                ->orderBy('id', 'asc')
                ->cursorPaginate(50)
        );

        return $this->data($collection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateOutcomeDetailTagRequest $request)
    {
        $data = $request->validated();
        if (!isset($data['project_id']) && $this->currentProjectId()) {
            $data['project_id'] = $this->currentProjectId();
        }

        $detailTag = MasterOutcomeDetailTag::create($data);
        return $this->success(new MasterResource($detailTag), 'Outcome Detail Tag created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterOutcomeDetailTag $masterOutcomeDetailTag)
    {
        return $this->data(new MasterResource($masterOutcomeDetailTag));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateOutcomeDetailTagRequest $request, MasterOutcomeDetailTag $masterOutcomeDetailTag)
    {
        $masterOutcomeDetailTag->update($request->validated());
        return $this->success(new MasterResource($masterOutcomeDetailTag), 'Outcome Detail Tag updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterOutcomeDetailTag $masterOutcomeDetailTag)
    {
        $masterOutcomeDetailTag->delete();
        return $this->success(null, 'Outcome Detail Tag deleted successfully');
    }
}
