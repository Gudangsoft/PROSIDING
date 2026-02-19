<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoScriptTags implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value) && (
            str_contains($value, '<script') ||
            str_contains($value, '</script>') ||
            str_contains($value, 'javascript:') ||
            str_contains($value, 'onerror=') ||
            str_contains($value, 'onclick=') ||
            str_contains($value, 'onload=')
        )) {
            $fail('The :attribute contains potentially dangerous content.');
        }
    }
}
