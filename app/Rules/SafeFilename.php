<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SafeFilename implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value)) {
            // Check for path traversal attempts
            if (str_contains($value, '..') || 
                str_contains($value, '/') || 
                str_contains($value, '\\')) {
                $fail('The :attribute contains invalid characters.');
            }

            // Check for null bytes
            if (str_contains($value, "\0")) {
                $fail('The :attribute contains invalid characters.');
            }

            // Check for dangerous extensions
            $dangerousExtensions = [
                'exe', 'bat', 'cmd', 'sh', 'php', 'php3', 'php4', 'php5', 
                'phtml', 'py', 'rb', 'pl', 'cgi', 'jsp', 'asp', 'aspx'
            ];

            $extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));
            if (in_array($extension, $dangerousExtensions)) {
                $fail('The :attribute has an unsafe file extension.');
            }
        }
    }
}
