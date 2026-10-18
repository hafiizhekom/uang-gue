<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreUpdateOutcomeCategoryRequest;
use App\Http\Resources\MasterCollection;
use App\Http\Resources\MasterResource;
use App\Models\MasterOutcomeCategory;
use App\Traits\HasProjectContext;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class MasterOutcomeCategoryController extends Controller implements HasMiddleware
{
    use HasProjectContext;

    public static function middleware(): array
    {
        return [
            // Pake Policy buat method tertentu
            new Middleware('can:view,master_outcome_category', only: ['show']),
            new Middleware('can:update,master_outcome_category', only: ['update']),
            new Middleware('can:delete,master_outcome_category', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projectId = $this->currentProjectId();

        $collection = MasterResource::collection(
            MasterOutcomeCategory::query()
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
    public function store(StoreUpdateOutcomeCategoryRequest $request)
    {
        $data = $request->validated();
        if (!isset($data['project_id']) && $this->currentProjectId()) {
            $data['project_id'] = $this->currentProjectId();
        }

        $outcomeCategory = MasterOutcomeCategory::create($data);
        return $this->success(new MasterResource($outcomeCategory), 'Outcome Category created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterOutcomeCategory $masterOutcomeCategory)
    {
        return $this->data(new MasterResource($masterOutcomeCategory));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateOutcomeCategoryRequest $request, MasterOutcomeCategory $masterOutcomeCategory)
    {
        $masterOutcomeCategory->update($request->validated());
        return $this->success(new MasterResource($masterOutcomeCategory), 'Outcome Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterOutcomeCategory $masterOutcomeCategory)
    {
        $masterOutcomeCategory->delete();
        return $this->success(null, 'Outcome Category deleted successfully');
    }
}
