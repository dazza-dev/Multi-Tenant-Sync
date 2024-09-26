<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExecuteJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = ['job' => 'required'];

        if ($this->job) {
            $jobConfig = config('jobs-available.'.$this->job);

            if ($jobConfig['allow_params']) {
                $rules['params'] = $jobConfig['validations'];
            }
        }

        return $rules;
    }
}
