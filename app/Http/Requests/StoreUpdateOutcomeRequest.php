<?php

namespace App\Http\Requests;

use App\Models\MasterPeriod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateOutcomeRequest extends FormRequest
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
        $this->merge([
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $hasDetail = $this->route('outcome') ? $this->route('outcome')->has_detail : $this->boolean('has_detail');

        $rules = [
            'master_period_id' => [
                'required',
                Rule::exists('master_periods', 'id')->where(fn ($q) => 
                    $q->where('user_id', auth()->id())->whereNull('deleted_at')
                ),
            ],
            'master_outcome_category_id' => [
                'required',
                Rule::exists('master_outcome_categories', 'id')->where(fn ($q) => 
                    $q->where('user_id', auth()->id())->whereNull('deleted_at')
                ),
            ],
            'master_outcome_type_id' => [
                'nullable',
                Rule::exists('master_outcome_types', 'id')->where(fn ($q) => 
                    $q->where('user_id', auth()->id())->whereNull('deleted_at')
                ),
            ],
            'master_payment_id' => [
                $hasDetail ? 'prohibited' : 'required',
                'nullable',
                Rule::exists('master_payments', 'id')->where(fn ($q) => 
                    $q->where('user_id', auth()->id())->whereNull('deleted_at')
                ),
            ],
            'title' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'amount' => [
                $hasDetail ? 'prohibited' : 'required',
                'numeric',
                'min:0'
            ],
            'note' => ['nullable', 'string'],
        ];

        if ($this->master_period_id && $this->date) {
            $period = MasterPeriod::find($this->master_period_id);

            if ($period) {
                $rules['date'][] = "after_or_equal:{$period->start_date}";
                $rules['date'][] = "before_or_equal:{$period->end_date}";
            }
        }

        if (!$this->route('outcome')) {
            $rules['user_id'] = ['required', 'exists:users,id'];
            $rules['has_detail'] = ['required', 'boolean'];
        }else{
            $rules['has_detail'] = ['prohibited'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'date.after_or_equal' => 'The date must be within the selected period start date.',
            'date.before_or_equal' => 'The date must be within the selected period end date.',
            'has_detail.prohibited' => 'The has detail field cannot be changed once created.',
            'amount.prohibited' => 'Amount cannot be set manually when outcome has details.',
        ];
    }
}
