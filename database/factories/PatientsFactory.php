<?php

namespace Database\Factories;

use App\Models\Patients;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PatientsFactory extends Factory
{
    protected $model = Patients::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'mother_name' => $this->faker->name(),
            'birthday' => Carbon::now(),
            'cpf' => $this->faker->randomNumber(3) . $this->faker->randomNumber(3) . $this->faker->randomNumber(3) . $this->faker->randomNumber(2),
            'cns' => $this->faker->randomNumber(3) . $this->faker->randomNumber(3) . $this->faker->randomNumber(3) . $this->faker->randomNumber(3) . $this->faker->randomNumber(3),
            'photo' => $this->faker->imageUrl(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
