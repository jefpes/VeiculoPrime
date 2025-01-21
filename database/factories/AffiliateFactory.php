<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AffiliateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type'  => $this->faker->randomElement(['Mãe', 'Pai', 'Amigo', 'Parente']),
            'name'  => $this->faker->name,
            'phone' => $this->faker->numerify('(##) #####-####'),
        ];
    }
}
