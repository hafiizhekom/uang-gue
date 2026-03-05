<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexIncomeRequest;
use App\Http\Requests\StoreUpdateIncomeRequest;
use App\Http\Resources\IncomeResource;
use App\Http\Resources\MasterResource;
use App\Models\Income;
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
        ->with(['type', 'payment'])
        ->latest('date')
        ->get();

        return IncomeResource::collection($incomes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateIncomeRequest $request)
    {
        //
        $data = $request->validated();
        return $this->success(new IncomeResource(Income::create($data)->load(['type', 'payment'])), 'Income created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Income $income)
    {
        //
        return $this->data(new IncomeResource($income->load(['type', 'payment'])));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateIncomeRequest $request, Income $income)
    {
        //
        $income->update($request->validated());
        return $this->success(new IncomeResource($income->load(['type', 'payment'])), 'Income updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income)
    {
        //
        $income->delete();
        return $this->success(null, 'Income deleted successfully');
    }
}
