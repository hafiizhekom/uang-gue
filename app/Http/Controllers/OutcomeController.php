<?php

namespace App\Http\Controllers;

use App\Models\Outcome;
use Illuminate\Http\Request;
use App\Http\Resources\OutcomeResource;

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
                ->with(['category', 'details.tags', 'details.payment'])
                ->withCount('details')->latest()->cursorPaginate(15)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        return DB::transaction(function () use ($request) {
            $outcome = Outcome::create([
                'user_id' => auth()->id(),
                'date' => $request->date,
                'title' => $request->title,
                'amount' => $request->has_detail ? 0 : $request->amount,
                'master_outcome_category_id' => $request->master_outcome_category_id,
                'has_detail' => $request->has_detail,
            ]);

            // Jika pas create langsung kirim array details (Hybrid approach)
            if ($request->has_detail && $request->has('details')) {
                foreach ($request->details as $item) {
                    $detail = $outcome->details()->create([
                        'title' => $item['title'],
                        'amount' => $item['amount'],
                        'master_outcome_payment_id' => $item['master_outcome_payment_id'],
                    ]);
                    if (!empty($item['tag_ids'])) $detail->tags()->attach($item['tag_ids']);
                }
                $outcome->update(['amount' => $outcome->details()->sum('amount')]);
            }

            return new OutcomeResource($outcome->load('details.tags'));
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Outcome $outcome)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Outcome $outcome)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outcome $outcome)
    {
        //
    }
}
