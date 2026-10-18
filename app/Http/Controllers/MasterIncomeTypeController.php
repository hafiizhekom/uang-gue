<?php

namespace App\Http\Controllers;

use App\Http\Requests\Master\StoreUpdateIncomeTypeRequest;
use App\Http\Resources\MasterResource;
use App\Models\MasterIncomeType;
use App\Traits\HasProjectContext;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class MasterIncomeTypeController extends Controller implements HasMiddleware
{
    use HasProjectContext;

    public static function middleware(): array
    {
        return [
            // Pake Policy buat method tertentu
            new Middleware('can:view,master_income_type', only: ['show']),
            new Middleware('can:update,master_income_type', only: ['update']),
            new Middleware('can:delete,master_income_type', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projectId = $this->currentProjectId();

        $collection = MasterResource::collection(
            MasterIncomeType::query()
                ->where(function ($query) use ($projectId) {
                    if ($projectId) {
                        $query->where('project_id', $projectId);
                    } else {
                        $query->where('user_id', auth()->id());
                    }
                })
                ->withCount('incomes')
                ->orderBy('id', 'asc')
                ->cursorPaginate(50)
        );

        return $this->data($collection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateIncomeTypeRequest $request)
    {
        $data = $request->validated();
        if (!isset($data['project_id']) && $this->currentProjectId()) {
            $data['project_id'] = $this->currentProjectId();
        }

        $incomeType = MasterIncomeType::create($data);
        return $this->success(new MasterResource($incomeType), 'Category created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterIncomeType $masterIncomeType)
    {
        return $this->data(new MasterResource($masterIncomeType));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateIncomeTypeRequest $request, MasterIncomeType $masterIncomeType)
    {
        $masterIncomeType->update($request->validated());
        return $this->success(new MasterResource($masterIncomeType), 'Income Type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterIncomeType $masterIncomeType)
    {
        $masterIncomeType->delete();
        return $this->success(null, 'Income Type deleted successfully');
    }
}
