<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexIncomeRequest;
use App\Http\Resources\IncomeResource;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class IncomeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // Pake Policy buat method tertentu
            new Middleware('can:view,income', only: ['show']),
            new Middleware('can:update,income', only: ['update']),
            new Middleware('can:delete,income', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexIncomeRequest $request)
    {
        //
        $incomes = Income::query()
        ->where('user_id', auth()->id())
        ->where('master_period_id', $request->master_period_id)
        ->with('type')
        ->latest('date')
        ->get();

        return IncomeResource::collection($incomes);
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
