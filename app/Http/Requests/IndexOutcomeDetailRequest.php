<?php

namespace App\Http\Requests;

use App\Traits\HasProjectContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexOutcomeDetailRequest extends FormRequest
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
        $projectId = $this->currentProjectId();

        return [
            'outcome_id' => [
                'required',
                Rule::exists('outcomes', 'id')
                    ->where(function ($query) use ($projectId) {
                        if ($projectId) {
                            $query->where('project_id', $projectId);
                        } else {
                            $query->where('user_id', auth()->id());
                        }
                        $query->whereNull('deleted_at');
                    }),
            ],
        ];
    }
}
