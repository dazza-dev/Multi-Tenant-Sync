<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use PHPSQLParser\PHPSQLParser;

class DBQuery implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $parser = new PHPSQLParser($value);
        $parsedQuery = $parser->parse($value);

        //
        if (! $parsedQuery) {
            $fail('validation.invalid_query')->translate([
                'value' => $value,
            ]);
        }
    }
}
