<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobListingRequest extends FormRequest
{
    // Only recruiters may create or edit jobs (ownership is checked by the policy)
    public function authorize(): bool
    {
        return $this->user()?->isRecruiter() ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|in:full-time,part-time,internship,remote',
            'salary_min' => 'required|integer|min:0',
            'salary_max' => 'required|integer|gte:salary_min',
            'description' => 'required|string|min:30',
        ];
    }

    public function messages(): array
    {
        return [
            'salary_max.gte' => 'The maximum salary must be greater than or equal to the minimum salary.',
            'description.min' => 'The description must be at least 30 characters.',
        ];
    }
}
