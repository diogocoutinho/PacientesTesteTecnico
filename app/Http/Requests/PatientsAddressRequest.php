<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientsAddressRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'integer'],
            'postal_code' => ['required'],
            'street' => ['required'],
            'number' => ['nullable', 'integer'],
            'neighborhood' => ['required'],
            'city' => ['required'],
            'state' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
