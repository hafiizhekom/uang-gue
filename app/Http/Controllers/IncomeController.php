<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use App\Http\Resources\IncomeResource;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $request->validate([
            'master_period_id' => 'required|exists:master_periods,id',
        ]);

        return IncomeResource::collection(
            Income::query()->where('user_id', auth()->id())
                ->where('master_period_id', $request->master_period_id)
                ->with('type')->latest()->cursorPaginate(15)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'title' => 'required|string',
            'amount' => 'required|numeric',
            'master_income_type_id' => 'required|exists:master_income_types,id',
            'date' => 'required|date',
        ]);
        $data['user_id'] = auth()->id();
        return new IncomeResource(Income::create($data));
    }

    /**
     * Display the specified resource.
     */
    public function show(Income $income)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Income $income)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income)
    {
        //
    }
}
