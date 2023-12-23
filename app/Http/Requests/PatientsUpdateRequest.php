<?php

namespace App\Http\Requests;

use App\Rules\CnsProvRule;
use App\Rules\CnsRule;
use Illuminate\Foundation\Http\FormRequest;

class PatientsUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'mother_name' => ['required'],
            'birthday' => ['required', 'date'],
            'cpf' => ['required', 'numeric', 'digits:11', 'regex:/[0-9]{11}/'],
            'cns' => ['required', 'numeric', new CnsRule()],
            'postal_code' => ['required', 'numeric', 'digits:8'],
            'street' => ['required'],
            'number' => ['required', 'numeric'],
            'complement' => ['nullable'],
            'neighborhood' => ['required'],
            'city' => ['required'],
            'state' => ['required'],
            'photo' => ['string', 'nullable', 'url'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
