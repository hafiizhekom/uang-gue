<?php

namespace App\Http\Requests\Master;

use App\Traits\HasProjectContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateOutcomeCategoryRequest extends FormRequest
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
        $id = $this->route('master_outcome_category');
        $projectId = $this->currentProjectId();

        $mergeData = [
            'user_id' => auth()->id(),
        ];

        if (!$id) {
            $mergeData['project_id'] = $projectId;
            $mergeData['slug'] = str($this->slug ?? $this->name)->slug()->toString();
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
        $id = $this->route('master_outcome_category');
        $projectId = $this->currentProjectId();

        $rules = [
            'user_id' => 'required|exists:users,id',
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'is_counted' => [
                'sometimes',
                'boolean',
            ],
        ];

        if (!$id) {
            $rules['project_id'] = 'required|exists:projects,id';
            $rules['slug'] = [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                Rule::unique('master_outcome_categories')
                    ->where('project_id', $projectId)
                    ->whereNull('deleted_at'),
            ];
        }

        return $rules;
    }
}
