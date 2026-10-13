<?php

namespace App\Http\Requests;

use App\Traits\HasProjectContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportRequest extends FormRequest
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
            'master_period_id' => [
                'required',
                Rule::exists('master_periods', 'id')->where(function ($q) use ($projectId) {
                    if ($projectId) {
                        $q->where('project_id', $projectId);
                    } else {
                        $q->where('user_id', auth()->id());
                    }
                    $q->whereNull('deleted_at');
                }),
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