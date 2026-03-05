<?php

namespace App\Http\Controllers;

use App\Http\Requests\Master\StoreUpdatePeriodRequest;
use App\Http\Resources\MasterResource;
use App\Models\MasterPeriod;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Controllers\Controller;

class MasterPeriodController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // Pake Policy buat method tertentu
            new Middleware('can:view,master_period', only: ['show']),
            new Middleware('can:update,master_period', only: ['update']),
            new Middleware('can:delete,master_period', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $collection = MasterResource::collection(
            MasterPeriod::query()
            ->where('user_id', auth()->id())
            ->withCount(['outcomes', 'incomes'])
            ->latest()
            ->cursorPaginate(10)
        );

        return $this->data($collection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdatePeriodRequest $request)
    {
        $data = MasterPeriod::create($request->validated());
        return $this->success(new MasterResource($data), 'Period created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterPeriod $masterPeriod)
    {
        //
        return $this->data(new MasterResource($masterPeriod));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdatePeriodRequest $request, MasterPeriod $masterPeriod)
    {
        //
        $masterPeriod->update($request->validated());
        return $this->success(new MasterResource($masterPeriod), 'Period updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterPeriod $masterPeriod)
    {
        //
        $masterPeriod->delete();
        return $this->success(null, 'Period deleted successfully', 200);
    }
}
