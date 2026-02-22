<?php

namespace App\Http\Controllers;

use App\Http\Requests\Master\StoreUpdateOutcomeDetailTagRequest;
use App\Models\MasterOutcomeDetailTag;
use Illuminate\Http\Request;
use App\Http\Resources\MasterResource;

class MasterOutcomeDetailTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $collection = MasterResource::collection(
            MasterOutcomeDetailTag::query()
            ->where('user_id', auth()->id())
            ->withCount('outcome_details')
            ->latest()
            ->cursorPaginate(10)
        );

        return $this->data($collection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateOutcomeDetailTagRequest $request)
    {
        //
        $data = MasterOutcomeDetailTag::create($request->validated());
        return $this->success(new MasterResource($data), 'Outcome Detail Tag created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterOutcomeDetailTag $masterOutcomeDetailTag)
    {
        //
        return $this->data(new MasterResource($masterOutcomeDetailTag));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateOutcomeDetailTagRequest $request, MasterOutcomeDetailTag $masterOutcomeDetailTag)
    {
        //
        $masterOutcomeDetailTag->update($request->validated());
        return $this->success(new MasterResource($masterOutcomeDetailTag), 'Outcome Detail Tag updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterOutcomeDetailTag $masterOutcomeDetailTag)
    {
        //
        $masterOutcomeDetailTag->delete();
        return $this->success(null, 'Outcome Detail Tag deleted successfully', 200);
    }
}
