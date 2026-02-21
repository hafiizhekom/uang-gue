<?php

namespace App\Http\Controllers;

use App\Models\MasterOutcomePayment;
use Illuminate\Http\Request;
use App\Http\Resources\MasterResource;

class MasterOutcomePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return MasterResource::collection(
            MasterOutcomePayment::query()
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
        $data = MasterOutcomePayment::create($request->validate(['name' => 'required|string']));
        return response()->json([
            'message' => 'Outcome Payment created successfully',
            'data'    => new MasterResource($data)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterOutcomePayment $masterOutcomePayment)
    {
        //
        return new MasterResource($masterOutcomePayment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterOutcomePayment $masterOutcomePayment)
    {
        //
        $masterOutcomePayment->update($request->validate(['name' => 'required|string']));
        return response()->json([
            'message' => 'Outcome Payment updated successfully',
            'data'    => new MasterResource($masterOutcomePayment)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterOutcomePayment $masterOutcomePayment)
    {
        //
        $masterOutcomePayment->delete();
        return response()->json([
            'message' => 'Outcome Payment deleted successfully'
        ], 200);
    }
}
