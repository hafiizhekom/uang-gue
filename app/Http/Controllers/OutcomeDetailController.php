<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateOutcomeDetailRequest;
use App\Http\Resources\OutcomeDetailResource;
use App\Models\OutcomeDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class OutcomeDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $request->validate([
            'outcome_id' => 'required|exists:outcomes,id',
        ]);
        return OutcomeDetailResource::collection(
            OutcomeDetail::query()
            ->where('outcome_id', $request->outcome_id)
            ->whereHas('outcome', function($q) {
                $q->where('user_id', auth()->id());
            })
            ->with(['payment', 'tags'])
            ->latest('date')
            ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateOutcomeDetailRequest $request)
    {
        //
        $data = $request->validated();
        $detail = OutcomeDetail::create(Arr::except($data, ['tags']));

        if (isset($data['tags'])) {
            $detail->tags()->sync($data['tags']);
        }

        return $this->success(
            new OutcomeDetailResource($detail->load(['payment', 'tags'])), 
            'Outcome detail created successfully', 
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(OutcomeDetail $outcomeDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateOutcomeDetailRequest $request, OutcomeDetail $outcomeDetail)
    {
        //
        $data = $request->validated();
        $detail = $outcomeDetail->update(Arr::except($data, ['tags']));

        if (isset($data['tags'])) {
            $outcomeDetail->tags()->sync($data['tags']);
        }

        return $this->success(new OutcomeDetailResource($outcomeDetail->load(['payment', 'tags'])), 'Outcome detail updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OutcomeDetail $outcomeDetail)
    {
        //
        $outcomeDetail->tags()->detach();
        $outcomeDetail->delete();
        return $this->success(null, 'Outcome detail deleted successfully');
    }
}
