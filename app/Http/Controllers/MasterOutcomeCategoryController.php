<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreUpdateOutcomeCategoryRequest;
use App\Http\Resources\MasterResource;
use App\Models\MasterOutcomeCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class MasterOutcomeCategoryController extends Controller implements HasMiddleware
{
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $collection = MasterResource::collection(
            MasterOutcomeCategory::query()
            ->where('user_id', auth()->id())
            ->withCount('outcomes')
            ->latest()
            ->cursorPaginate(10)
        );
        return $this->data($collection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateOutcomeCategoryRequest $request)
    {
        //
        $data = MasterOutcomeCategory::create($request->validated());
        return $this->success(new MasterResource($data), 'Outcome Category created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterOutcomeCategory $masterOutcomeCategory)
    {
        //
        return $this->data(new MasterResource($masterOutcomeCategory));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateOutcomeCategoryRequest $request, MasterOutcomeCategory $masterOutcomeCategory)
    {
        //
        $masterOutcomeCategory->update($request->validated());
        return $this->success(new MasterResource($masterOutcomeCategory), 'Outcome Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterOutcomeCategory $masterOutcomeCategory)
    {
        //
        $masterOutcomeCategory->delete();
        return $this->success(null, 'Outcome Category deleted successfully');
    }
}
