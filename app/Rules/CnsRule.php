<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CnsRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) != 15) {
            $fail("O atributo $attribute deve ter exatamente 15 caracteres.");
            return;
        }

        $firstChar = substr($value, 0, 1);

        if ($firstChar == 1 || $firstChar == 2) {
            if (!$this->validateTemporaryCns($value)) {
                $fail("O CNS fornecido para $attribute é inválido.");
            }
        } elseif ($firstChar == 7 || $firstChar == 8 || $firstChar == 9) {
            if (!$this->validatePermanentCns($value)) {
                $fail("O CNS fornecido para $attribute é inválido.");
            }
        } else {
            $fail("O CNS fornecido para $attribute é inválido.");
        }
    }

    private function validateTemporaryCns($value): bool
    {
        $pis = substr($value, 0, 11);
        $sum = $this->calculateSum($pis);

        $rest = $sum % 11;
        $dv = 11 - $rest;

        if ($dv == 11) {
            $dv = 0;
        }

        if ($dv == 10) {
            $sum += 2;
            $rest = $sum % 11;
            $dv = 11 - $rest;
            $result = $pis . "001" . strval($dv);
        } else {
            $result = $pis . "000" . strval($dv);
        }
        return $value == $result;
    }

    private function validatePermanentCns($value): bool
    {
        $sum = $this->calculateSum($value);

        return $sum % 11 == 0;
    }

    private function calculateSum($value): float|int
    {
        $sum = 0;

        for ($i = 0; $i < strlen($value); $i++) {
            $sum += intval($value[$i]) * (15 - $i);
        }

        return $sum;
    }
}
