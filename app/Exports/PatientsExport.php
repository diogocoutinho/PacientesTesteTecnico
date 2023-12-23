<?php

namespace App\Exports;

use App\Models\Patients;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class PatientsExport implements FromCollection
{
    use Exportable;

    /**
     * @inheritDoc
     */
    public function collection()
    {
        return Patients::with('address')->get();
    }
}
