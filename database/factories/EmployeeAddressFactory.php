<?php

namespace Database\Factories;

use App\Enums\States;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeAddress>
 */
class EmployeeAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'zip_code'     => $this->faker->numerify('#####-###'),
            'street'       => $this->faker->streetName,
            'number'       => $this->faker->buildingNumber,
            'neighborhood' => 'Neighborhood',
            'city_id'      => $this->faker->randomNumber(1, 10),
            'state'        => $this->faker->randomElement(array_map(fn ($case) => $case->value, States::cases())),
            'complement'   => 'Complement test',
        ];
    }
}
