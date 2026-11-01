<?php

namespace App\Http\Requests\Master;

use App\Traits\HasProjectContext;
use Illuminate\Foundation\Http\FormRequest;

class StoreUpdatePeriodRequest extends FormRequest
{
    use HasProjectContext;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation()
    {
        $mergeData = [
            'user_id' => auth()->id(),
        ];

        if (!$this->route('master_period')) {
            $mergeData['project_id'] = $this->currentProjectId();
        }

        $this->merge($mergeData);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('master_period');
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'start_date' => [
                'required',
                'date',
            ],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],
            'opening_balance' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ];

        if (!$id) {
            $rules['user_id'] = 'required|exists:users,id';
            $rules['project_id'] = 'required|exists:projects,id';
        }

        return $rules;
    }
}
