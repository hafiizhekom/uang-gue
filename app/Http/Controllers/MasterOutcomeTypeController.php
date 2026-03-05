<?php

namespace App\Http\Controllers;

use App\Http\Requests\Master\StoreUpdateOutcomeTypeRequest;
use App\Models\MasterOutcomeType;
use Illuminate\Http\Request;
use App\Http\Resources\MasterResource;

class MasterOutcomeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $collection = MasterResource::collection(
            MasterOutcomeType::query()
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
    public function store(StoreUpdateOutcomeTypeRequest $request)
    {
        //
        $data = MasterOutcomeType::create($request->validated());
        return $this->success(new MasterResource($data), 'Outcome Type created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterOutcomeType $masterOutcomeType)
    {
        //
        return new MasterResource($masterOutcomeType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateOutcomeTypeRequest $request, MasterOutcomeType $masterOutcomeType)
    {
        //
        $masterOutcomeType->update($request->validated());
        return $this->success(new MasterResource($masterOutcomeType), 'Outcome Type updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterOutcomeType $masterOutcomeType)
    {
        //
        $masterOutcomeType->delete();
        return $this->success(null, 'Outcome Type deleted successfully', 200);
    }
}
