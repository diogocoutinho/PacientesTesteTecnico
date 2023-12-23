<?php

namespace Tests\Feature;

use App\Models\Patients;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PatientsControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testIndex()
    {
        $response = $this->get(route('patients.index'));

        $response->assertStatus(200);
    }

    public function testStore()
    {
        $patientData = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'mother_name' => $this->faker->name,
            'birthday' => $this->faker->date,
            'cpf' => $this->faker->randomNumber(3) . $this->faker->randomNumber(3) . $this->faker->randomNumber(3) . $this->faker->randomNumber(2),
            'cns' => $this->faker->randomNumber(3) . $this->faker->randomNumber(3) . $this->faker->randomNumber(3) . $this->faker->randomNumber(3) . $this->faker->randomNumber(3),
            'photo' => $this->faker->imageUrl(),
        ];

        $response = $this->post(route('patients.store'), $patientData);

        $response->assertStatus(302);
    }

    public function testShow()
    {
        $patient = Patients::factory()->create();

        $response = $this->get(route('patients.show', $patient->id));

        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $patient = Patients::factory()->create();

        $patientData = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'mother_name' => $this->faker->name,
            'birthday' => $this->faker->date,
            'cpf' => $this->faker->randomNumber(9),
            'cns' => $this->faker->randomNumber(15),
            'photo' => $this->faker->imageUrl(),
        ];

        $response = $this->put(route('patients.update', $patient->id), $patientData);

        $response->assertStatus(302);
    }

    public function testDestroy()
    {
        $patient = Patients::factory()->create();

        $response = $this->delete(route('patients.destroy', $patient->id));

        $response->assertStatus(200);
    }
}
