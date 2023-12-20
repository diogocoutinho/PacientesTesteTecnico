<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CnsProvRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cns = trim($attribute);
        if (strlen($cns) !== 15) {
            $fail('O CNS deve ter 15 dígitos');
        }

        $soma = array_sum(array_map(function ($x, $y) {
            return $x * $y;
        }, str_split($cns), array_reverse(range(15, 1))));

        $rest = $soma % 11;
        if ($rest !== 0) {
            $fail('O CNS é inválido');
        }
    }
}
