<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use PHPSQLParser\PHPSQLParser;

class SelectWithRequiredFields implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $parser = new PHPSQLParser($value);
        $parsedQuery = $parser->parse($value);

        // Get selected fields
        $selectedFields = array_map(function ($field) {
            return isset($field['alias']['name']) ? $field['alias']['name'] : $field['base_expr'];
        }, $parsedQuery['SELECT']);

        // Required fields
        $requiredFields = ['db_database', 'db_username', 'db_password'];

        // Check if all required fields are selected
        $missingFields = [];
        foreach ($requiredFields as $field) {
            if (! in_array($field, $selectedFields)) {
                $missingFields[] = $field;
            }
        }

        // Check if any required fields are missing
        if (count($missingFields) > 0) {
            $fail('validation.missing_required_field_in_query')->translate([
                'missing_fields' => implode(', ', $missingFields),
            ]);
        }
    }
}
