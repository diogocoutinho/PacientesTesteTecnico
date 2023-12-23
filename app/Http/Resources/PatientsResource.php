<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Patients */
class PatientsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'mother_name' => $this->mother_name,
            'birthday' => $this->birthday,
            'cpf' => $this->cpf,
            'cns' => $this->cns,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

