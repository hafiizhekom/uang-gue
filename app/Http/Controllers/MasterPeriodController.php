<?php

namespace App\Http\Controllers;

use App\Http\Resources\MasterPeriodResource;
use App\Models\MasterPeriod;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MasterPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return MasterPeriodResource::collection(
            MasterPeriod::query()
            ->where('user_id', auth()->id())
            ->withCount(['outcomes', 'incomes'])
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
        $val = $request->validate([
            'name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);
        $val['user_id'] = auth()->id();
        return response()->json([
            'message' => 'Period created successfully',
            'data'    => new MasterPeriodResource(MasterPeriod::create($val))
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterPeriod $masterPeriod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterPeriod $masterPeriod)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterPeriod $masterPeriod)
    {
        //
    }
}
