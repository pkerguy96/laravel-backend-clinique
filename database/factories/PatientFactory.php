<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patient;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Patient::class;

    public function definition()
    {
        $mutuelleOptions = [
            "Mamdat",
            "CNIA SAADA",
            "CNOPS",
            "GENERAL",
            "CNSS",
            "MFAR",
            "WATANIA",
            "ZURICH",
            "ATLANTA",
            "AXA",
            "WAFA ASURANCE",
        ];
        return [
            'doctor_id' => 4,
            'nom' => $this->faker->firstName,
            'prenom' => $this->faker->lastName,
            'cin' => $this->faker->unique()->ean13,
            'date' => $this->faker->date,
            'address' => $this->faker->address,
            'sex' => $this->faker->randomElement(['male', 'female']),
            'phone_number' => $this->faker->unique()->phoneNumber,
            'mutuelle' => $this->faker->randomElement($mutuelleOptions),
            'note' => $this->faker->sentence,
        ];
    }
}
