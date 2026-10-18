<?php

namespace App\Http\Requests;

use App\Traits\HasProjectContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexIncomeRequest extends FormRequest
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
            'master_period_id' => [
                'required',
                Rule::exists('master_periods', 'id')
                    ->where('project_id', $this->currentProjectId())
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
