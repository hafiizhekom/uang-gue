<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
class StoreUpdatePeriodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation()
    {
        $this->merge(['user_id' => auth()->id()]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $id = $this->route('master_income_type');
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
        ];

        if (!$id) {
            $rules['user_id'] = 'required|exists:users,id';
        }

        return $rules;
    }
}
