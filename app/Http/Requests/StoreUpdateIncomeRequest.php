<?php

namespace App\Http\Requests;

use App\Models\MasterPeriod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreUpdateIncomeRequest extends FormRequest
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

        $rules = [
            'master_period_id' => [
                'required',
                Rule::exists('master_periods', 'id')->where(fn($q) => $q->where('user_id', auth()->id())->whereNull('deleted_at')),
            ],
            'master_income_type_id' => [
                'required',
                Rule::exists('master_income_types', 'id')->where(fn($q) => $q->where('user_id', auth()->id())->whereNull('deleted_at')),
            ],
            'master_payment_id' => [
                'required',
                Rule::exists('master_payments', 'id')->where(fn($q) => $q->where('user_id', auth()->id())->whereNull('deleted_at')),
            ],
            'title'  => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'date'   => ['required', 'date'],
            'note'   => ['nullable', 'string'],
        ];

        // Custom Validation: Ensure date is within period range
        if ($this->master_period_id && $this->date) {
            $period = MasterPeriod::find($this->master_period_id);

            if ($period) {
                $rules['date'][] = "after_or_equal:{$period->start_date}";
                $rules['date'][] = "before_or_equal:{$period->end_date}";
            }
        }

        // Only require user_id on creation (POST), not on update (PATCH/PUT)
        if (!$this->route('income')) {
            $rules['user_id'] = ['required', 'exists:users,id'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'date.after_or_equal' => 'The date must be within the selected period start date.',
            'date.before_or_equal' => 'The date must be within the selected period end date.',
        ];
    }
}
