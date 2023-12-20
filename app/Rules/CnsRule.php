<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CnsRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cns = trim($attribute);
        if (strlen($cns) !== 15) {
            $fail('O CNS deve ter 15 dígitos');
        }

        $pis = substr($cns, 0, 11);
        $soma = array_sum(array_map(function ($x, $y) {
            return $x * $y;
        }, str_split($pis), range(15, 5)));

        $dv = 11 - ($soma % 11);
        if ($dv === 11 || $dv === 10) {
            $dv = 0;
        }

        $resultant = $pis . str_pad((string)$dv, 3, '0', STR_PAD_LEFT);
        if ($resultant !== $cns) {
            $fail('O CNS é inválido');
        }
    }
}
