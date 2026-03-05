<?php

namespace App\Http\Controllers;

use App\Http\Requests\Master\StoreUpdatePaymentRequest;
use App\Models\MasterPayment;
use Illuminate\Http\Request;
use App\Http\Resources\MasterResource;

class MasterPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $collection = MasterResource::collection(
            MasterPayment::query()
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
    public function store(StoreUpdatePaymentRequest $request)
    {
        //
        $data = MasterPayment::create($request->validated());
        return $this->success(new MasterResource($data), 'Outcome Payment created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterPayment $masterPayment)
    {
        //
        return new MasterResource($masterPayment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdatePaymentRequest $request, MasterPayment $masterPayment)
    {
        //
        $masterPayment->update($request->validated());
        return $this->success(new MasterResource($masterPayment), 'Outcome Payment updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterPayment $masterPayment)
    {
        //
        $masterPayment->delete();
        return $this->success(null, 'Outcome Payment deleted successfully');
    }
}
