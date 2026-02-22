<?php

namespace App\Http\Controllers;

use App\Http\Requests\Master\StoreUpdateOutcomePaymentRequest;
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
        $collection = MasterResource::collection(
            MasterOutcomePayment::query()
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
    public function store(StoreUpdateOutcomePaymentRequest $request)
    {
        //
        $data = MasterOutcomePayment::create($request->validated());
        return $this->success(new MasterResource($data), 'Outcome Payment created successfully', 201);
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
        $masterOutcomePayment->update($request->validated());
        return $this->success(new MasterResource($masterOutcomePayment), 'Outcome Payment updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterOutcomePayment $masterOutcomePayment)
    {
        //
        $masterOutcomePayment->delete();
        return $this->success(null, 'Outcome Payment deleted successfully');
    }
}
