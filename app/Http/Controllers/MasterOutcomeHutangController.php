<?php

namespace App\Http\Controllers;

use App\Models\MasterOutcomeHutang;
use Illuminate\Http\Request;
use App\Http\Resources\MasterResource;

class MasterOutcomeHutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return MasterResource::collection(
            MasterOutcomeHutang::query()
            ->where('user_id', auth()->id())
            ->withCount('outcomes')
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
        $data = MasterOutcomeHutang::create($request->validate(['name' => 'required|string']));
        return response()->json([
            'message' => 'Outcome Hutang created successfully',
            'data'    => new MasterResource($data)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterOutcomeHutang $masterOutcomeHutang)
    {
        //
        return new MasterResource($masterOutcomeHutang);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterOutcomeHutang $masterOutcomeHutang)
    {
        //
        $masterOutcomeHutang->update($request->validate(['name' => 'required|string']));
        return response()->json([
            'message' => 'Outcome Hutang updated successfully',
            'data'    => new MasterResource($masterOutcomeHutang)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterOutcomeHutang $masterOutcomeHutang)
    {
        //
        $masterOutcomeHutang->delete();
        return response()->json([
            'message' => 'Outcome Hutang deleted successfully'
        ], 200);
    }
}
