<?php

namespace App\Http\Requests;

use App\Rules\CnsProvRule;
use App\Rules\CnsRule;
use Illuminate\Foundation\Http\FormRequest;

class PatientsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'mother_name' => ['required'],
            'birthday' => ['required', 'date'],
            'cpf' => ['required', 'numeric', 'unique:patients', 'size:11', 'regex:/[0-9]{11}/'],
            'cns' => ['required', 'numeric', 'unique:patients', new CnsRule(), new CnsProvRule()],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
