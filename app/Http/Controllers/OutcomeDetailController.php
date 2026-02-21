<?php

namespace App\Http\Controllers;

use App\Models\OutcomeDetail;
use Illuminate\Http\Request;

class OutcomeDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        return DB::transaction(function () use ($request) {
            $detail = OutcomeDetail::create([
                'outcome_id' => $request->outcome_id,
                'title' => $request->title,
                'amount' => $request->amount,
                'master_outcome_payment_id' => $request->master_outcome_payment_id,
            ]);

            if ($request->has('tag_ids')) {
                $detail->tags()->attach($request->tag_ids);
            }

            // Sync total amount di parent setiap kali ada detail baru
            $detail->outcome->update(['amount' => $detail->outcome->details()->sum('amount')]);

            return new OutcomeDetailResource($detail->load('tags'));
        });
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
    public function update(Request $request, OutcomeDetail $outcomeDetail)
    {
        //
        $outcomeDetail->update($request->all());
        
        if ($request->has('tag_ids')) {
            $outcomeDetail->tags()->sync($request->tag_ids);
        }

        // Sync total amount parent
        $outcomeDetail->outcome->update(['amount' => $outcomeDetail->outcome->details()->sum('amount')]);

        return new OutcomeDetailResource($outcomeDetail->load('tags'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OutcomeDetail $outcomeDetail)
    {
        //
        $parent = $outcomeDetail->outcome;
        $outcomeDetail->delete();
        
        // Sync total amount parent setelah delete
        $parent->update(['amount' => $parent->details()->sum('amount')]);

        return response()->json(['message' => 'Deleted and Parent Synced']);
    }
}
