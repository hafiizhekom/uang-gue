<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportRequest extends FormRequest
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
            'master_period_id' => [
                'required',
                Rule::exists('master_periods', 'id')->where('user_id', auth()->id())
            ],
        ];
    }

    protected function prepareForValidation()
    {
        // Route is GET /report/{period_id} — merge the route param in
        // so it validates the same way master_period_id does elsewhere.
        $this->merge([
            'master_period_id' => $this->route('period_id'),
        ]);
    }
}