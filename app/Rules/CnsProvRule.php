<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CnsProvRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) != 15){
            $fail('O CNS deve conter 15 dígitos.');
            return;
        }

        $sum = 0;

        for ($i = 0; $i < 15; $i++) {
            $sum += intval($value[$i]) * (15 - $i);
        }

        $rest = $sum % 11;

        if ($rest != 0) {
            $fail('O CNS é inválido.');
        }
    }
}
