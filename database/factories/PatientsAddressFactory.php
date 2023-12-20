<?php

namespace Database\Factories;

use App\Models\PatientsAddress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PatientsAddressFactory extends Factory
{
    protected $model = PatientsAddress::class;

    public function definition(): array
    {
        return [
            'patient_id' => $this->faker->randomNumber(),
            'postal_code' => $this->faker->postcode(),
            'street' => $this->faker->streetName(),
            'number' => $this->faker->randomNumber(),
            'neighborhood' => $this->faker->word(),
            'city' => $this->faker->city(),
            'state' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
