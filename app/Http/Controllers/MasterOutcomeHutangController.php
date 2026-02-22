<?php

namespace App\Http\Controllers;

use App\Http\Requests\Master\StoreUpdateOutcomeHutangRequest;
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
        $collection = MasterResource::collection(
            MasterOutcomeHutang::query()
            ->where('user_id', auth()->id())
            ->withCount('outcomes')
            ->latest()
            ->cursorPaginate(10)
        );

        return $this->data($collection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateOutcomeHutangRequest $request)
    {
        //
        $data = MasterOutcomeHutang::create($request->validated());
        return $this->success(new MasterResource($data), 'Outcome Hutang created successfully', 201);
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
    public function update(StoreUpdateOutcomeHutangRequest $request, MasterOutcomeHutang $masterOutcomeHutang)
    {
        //
        $masterOutcomeHutang->update($request->validated());
        return $this->success(new MasterResource($masterOutcomeHutang), 'Outcome Hutang updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterOutcomeHutang $masterOutcomeHutang)
    {
        //
        $masterOutcomeHutang->delete();
        return $this->success(null, 'Outcome Hutang deleted successfully', 200);
    }
}
