<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'         => $this->faker->company,
            'slug'         => $this->faker->unique()->word(),
            'active'       => true,
            'zip_code'     => $this->faker->postcode,
            'state'        => $this->faker->state,
            'city'         => $this->faker->city,
            'neighborhood' => $this->faker->streetName,
            'street'       => $this->faker->streetName,
            'number'       => $this->faker->buildingNumber,
            'complement'   => $this->faker->secondaryAddress,
        ];
    }

    public function withPhone(int $count = 1)
    {
        return $this->afterCreating(function (Model $model) use ($count) {
            for ($i = 0; $i < $count; $i++) {
                $model->phones()->create(array_merge(
                    PhoneFactory::new()->definition()
                ));
            }
        });
    }
}
