<?php

/**
 * Validation rule enforcing mixed character categories for user passwords.
 *
 * Inputs: password string supplied via form submission.
 * Outputs: validation pass when at least two character classes are present; otherwise adds a descriptive error message.
 */

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Ensures passwords include more than one character type.
 */
class PasswordStrength implements ValidationRule
{
    /**
     * Validate the given password value.
     *
     * Inputs: attribute name (string), password value (mixed), fail callback (Closure).
     * Outputs: void; invokes fail callback with a message when requirements are not met.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string): void $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $password = (string) $value;

        $categories = 0;
        $categories += (bool) preg_match('/[A-Z]/', $password) ? 1 : 0;
        $categories += (bool) preg_match('/[a-z]/', $password) ? 1 : 0;
        $categories += (bool) preg_match('/\\d/', $password) ? 1 : 0;
        $categories += (bool) preg_match('/[^A-Za-z0-9]/', $password) ? 1 : 0;

        if ($categories < 4) {
            $fail('Your password must include the following: uppercase letters, lowercase letters, numbers, and special characters.');
        }
    }
}
