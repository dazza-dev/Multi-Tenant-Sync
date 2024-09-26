<?php

namespace App\Http\Requests;

use App\Rules\DBQuery;
use App\Rules\OnlySelectQuery;
use App\Rules\SelectWithRequiredFields;
use Illuminate\Foundation\Http\FormRequest;

class CreateProjectRequest extends FormRequest
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
        return [
            'name' => 'required',
            'db_host' => 'required',
            'db_port' => 'nullable|numeric',
            'db_database' => 'required',
            'db_username' => 'required',
            'db_password' => 'required',
            'raw_query' => ['nullable', 'string', new DBQuery, new OnlySelectQuery, new SelectWithRequiredFields],
            'bindings' => 'nullable|array',
        ];
    }
}
