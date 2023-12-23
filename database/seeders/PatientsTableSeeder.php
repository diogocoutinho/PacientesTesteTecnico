<?php

namespace Database\Seeders;

use App\Models\Patients;
use App\Models\PatientsAddress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $patients = Patients::factory(50)->create();
        if ($patients) {
            $patients->each(function ($patient) {
                $patient->address()->save(PatientsAddress::factory()->make());
            });
        }
    }
}
