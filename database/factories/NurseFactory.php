<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Nurse;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Nurse>
 */
class NurseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Nurse::class;

    public function definition()
    {
        return [
            'doctor_id' => 1,
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'cin' => $this->faker->unique()->ean13,
            'date' => $this->faker->date,
            'address' => $this->faker->address,
            'sex' => $this->faker->randomElement(['male', 'female']),
            'phone_number' => $this->faker->unique()->phoneNumber,
        ];
    }
}
