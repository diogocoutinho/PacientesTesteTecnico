<?php

namespace Tests\Unit;

use App\Models\Patients;
use App\Models\PatientsAddress;
use PHPUnit\Framework\TestCase;

class PatientTest extends TestCase
{
    public function testBasic()
    {
        // Crie um paciente com um endereço associado
        $patient = Patients::factory()->create();
        $address = PatientsAddress::factory()->create(['patient_id' => $patient->id]);

        // Verifique se o paciente foi criado
        $this->assertNotNull($patient);
        $this->assertInstanceOf(Patients::class, $patient);

        // Verifique se o endereço está associado ao paciente
        $this->assertNotNull($address);
        $this->assertInstanceOf(PatientsAddress::class, $address);
        $this->assertEquals($patient->id, $address->patient_id);
    }
}
