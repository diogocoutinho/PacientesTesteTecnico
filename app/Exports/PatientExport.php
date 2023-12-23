<?php

namespace App\Exports;

use App\Models\Patients;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class PatientExport implements FromCollection
{
    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return Patients::all();
    }
}
