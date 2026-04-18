<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DashboardRequest extends FormRequest
{
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
        $userId = auth()->id();
        $today = now()->toDateString();

        $period = \App\Models\MasterPeriod::where('user_id', $userId)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first() 
            ?? \App\Models\MasterPeriod::where('user_id', $userId)->latest()->first();

        if (!$period) {
            // Panggil method error() dari trait ApiResponse lo
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
