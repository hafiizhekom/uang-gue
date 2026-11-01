<?php

namespace App\Http\Requests;

use App\Models\Outcome;
use App\Traits\HasProjectContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateOutcomeDetailRequest extends FormRequest
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

        $rules = [
            'outcome_id' => [
                'required',
                function ($attribute, $value, $fail) use ($projectId) {
                    $outcome = Outcome::where('id', $value)
                        ->where(function ($query) use ($projectId) {
                            if ($projectId) {
                                $query->where('project_id', $projectId);
                            } else {
                                $query->where('user_id', auth()->id());
                            }
                        })
                        ->first();

                    if (!$outcome) {
                        $fail('The selected outcome is invalid or does not belong to this project.');
                        return;
                    }

                    if (!$outcome->has_detail) {
                        $fail('The selected outcome does not support details.');
                    }
                },
            ],
            'master_payment_id' => [
                'required',
                Rule::exists('master_payments', 'id')->where(fn($q) => $q->where('project_id', $projectId)->whereNull('deleted_at')),
            ],
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'date' => [
                'required', 
                'date',
                function ($attribute, $value, $fail) {
                    $outcome = Outcome::with('period')->find($this->outcome_id);
                    if ($outcome && $outcome->period) {
                        $date = \Carbon\Carbon::parse($value);
                        $start = \Carbon\Carbon::parse($outcome->period->start_date);
                        $end = \Carbon\Carbon::parse($outcome->period->end_date);

                        if ($date->lt($start) || $date->gt($end)) {
                            $fail("The detail date must be between {$outcome->period->start_date} and {$outcome->period->end_date}.");
                        }
                    }
                }
            ],
            'note' => ['nullable', 'string'],
            'tags' => ['nullable', 'array'],
            'tags.*' => [
                'integer',
                Rule::exists('master_outcome_detail_tags', 'id')->where(fn ($q) => 
                    $q->where('project_id', $projectId)->whereNull('deleted_at')
                ),
            ],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'tags.*.exists' => 'One or more selected tags are invalid.',
        ];
    }
}
