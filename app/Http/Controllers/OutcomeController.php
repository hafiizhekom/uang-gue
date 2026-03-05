<?php

namespace App\Http\Controllers;

use App\Models\Outcome;
use Illuminate\Http\Request;
use App\Http\Resources\OutcomeResource;
use App\Http\Requests\StoreUpdateOutcomeRequest;

class OutcomeController extends Controller
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
        return OutcomeResource::collection(
            Outcome::query()->where('user_id', auth()->id())
                ->where('master_period_id', $request->master_period_id)
                ->with(['category', 'payment', 'type'])
                ->withCount('details')->latest('date')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateOutcomeRequest $request)
    {
        //
        $data = $request->validated();
        return $this->success(new OutcomeResource(Outcome::create($data)->load(['category', 'payment', 'type'])), 'Outcome created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Outcome $outcome)
    {
        //
        return $this->data(new OutcomeResource($outcome->load(['category', 'payment', 'type'])));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateOutcomeRequest $request, Outcome $outcome)
    {
        //
        $outcome->update($request->validated());
        return $this->success(new OutcomeResource($outcome->load(['category', 'payment', 'type'])), 'Outcome updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outcome $outcome)
    {
        //
        $outcome->delete();
        return $this->success(null, 'Outcome deleted successfully');
    }
}
