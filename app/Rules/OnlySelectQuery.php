<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OnlySelectQuery implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (stripos(trim($value), 'SELECT') !== 0) {
            $fail('validation.is_not_a_select_query')->translate([
                'value' => $value,
            ]);
        }
    }
}
