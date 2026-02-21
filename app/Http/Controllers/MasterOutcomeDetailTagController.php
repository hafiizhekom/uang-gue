<?php

namespace App\Http\Controllers;

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
        return MasterResource::collection(
            MasterOutcomeDetailTag::query()
            ->where('user_id', auth()->id())
            ->withCount('outcomeDetails')
            ->latest()
            ->cursorPaginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = MasterOutcomeDetailTag::create($request->validate(['name' => 'required|string']));
        return response()->json([
            'message' => 'Outcome Detail Tag created successfully',
            'data'    => new MasterResource($data)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterOutcomeDetailTag $masterOutcomeDetailTag)
    {
        //
        return new MasterResource($masterOutcomeDetailTag);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterOutcomeDetailTag $masterOutcomeDetailTag)
    {
        //
        $masterOutcomeDetailTag->update($request->validate(['name' => 'required|string']));
        return response()->json([
            'message' => 'Outcome Detail Tag updated successfully',
            'data'    => new MasterResource($masterOutcomeDetailTag)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterOutcomeDetailTag $masterOutcomeDetailTag)
    {
        //
        $masterOutcomeDetailTag->delete();
        return response()->json([
            'message' => 'Outcome Detail Tag deleted successfully'
        ], 200);
    }
}
