<?php

namespace App\Http\Requests;

use App\Models\MasterPeriod;
use App\Traits\HasProjectContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DashboardRequest extends FormRequest
{
    use HasProjectContext;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    protected function prepareForValidation()
    {
        $projectId = $this->currentProjectId();
        $today = now()->toDateString();

        $query = MasterPeriod::query();
        if ($projectId) {
            $query->where('project_id', $projectId);
        } else {
            $query->where('user_id', auth()->id());
        }

        $period = (clone $query)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first() 
            ?? (clone $query)->latest()->first();

        if (!$period) {
            throw new HttpResponseException(
                response()->json([
                    'status'  => 'Error',
                    'message' => 'Periode tidak ditemukan. Silakan buat periode master terlebih dahulu.',
                    'errors'  => 'Period not found'
                ], 404)
            );
        }

        $this->merge(['active_period' => $period]);
    }
}
