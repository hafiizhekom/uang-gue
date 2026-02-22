<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateOutcomeCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
        return auth()->check();
    }

    protected function prepareForValidation()
    {
        $id = $this->route('master_outcome_category');
        if (!$id) {
            $this->merge([
                'user_id' => auth()->id(),
                'slug'    => str($this->slug ?? $this->name)->slug()->toString(),
            ]);
        } else {
            $this->merge(['user_id' => auth()->id()]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $id = $this->route('master_outcome_category');
        $rules = [
            //
            'user_id' => 'required|exists:users,id',
            'name' => [
                'required',
                'string',
                'max:255'
            ]
        ];

        if (!$id) {
            $rules['slug'] = [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('master_outcome_categories')->where('user_id', auth()->id())
            ];
        }

        return $rules;
    }
}
