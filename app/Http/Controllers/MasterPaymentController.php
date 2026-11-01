<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreUpdatePaymentRequest;
use App\Http\Resources\MasterResource;
use App\Models\MasterPayment;
use App\Traits\HasProjectContext;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MasterPaymentController extends Controller implements HasMiddleware
{
    use HasProjectContext;

    public static function middleware(): array
    {
        return [
            new Middleware('can:view,master_payment', only: ['show']),
            new Middleware('can:update,master_payment', only: ['update']),
            new Middleware('can:delete,master_payment', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projectId = $this->currentProjectId();

        $collection = MasterResource::collection(
            MasterPayment::query()
                ->where(function ($query) use ($projectId) {
                    if ($projectId) {
                        $query->where('project_id', $projectId);
                    } else {
                        $query->where('user_id', auth()->id());
                    }
                })
                ->withCount('outcomes')
                ->withCount('outcome_details')
                ->withCount('incomes')
                ->orderBy('id', 'asc')
                ->cursorPaginate(50)
        );

        return $this->data($collection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdatePaymentRequest $request)
    {
        $data = $request->validated();
        if (!isset($data['project_id']) && $this->currentProjectId()) {
            $data['project_id'] = $this->currentProjectId();
        }

        $payment = MasterPayment::create($data);
        return $this->success(new MasterResource($payment), 'Outcome Payment created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterPayment $masterPayment)
    {
        return new MasterResource($masterPayment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdatePaymentRequest $request, MasterPayment $masterPayment)
    {
        $masterPayment->update($request->validated());
        return $this->success(new MasterResource($masterPayment), 'Outcome Payment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterPayment $masterPayment)
    {
        $masterPayment->delete();
        return $this->success(null, 'Outcome Payment deleted successfully');
    }
}
