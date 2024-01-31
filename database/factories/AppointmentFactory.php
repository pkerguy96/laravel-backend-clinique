<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Appointment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'doctor_id' => 1, // Set the doctor_id to 1
            'patient_id' => $this->faker->unique()->numberBetween(1, 20), // Adjust the range as needed
            'title' => $this->faker->sentence,
            'date' => $this->faker->dateTimeThisMonth,
            'note' => $this->faker->sentence,
        ];
    }
}
