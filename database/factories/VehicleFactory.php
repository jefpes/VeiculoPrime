<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purchase_date'     => $this->faker->date(),
            'fipe_price'        => $this->faker->randomFloat(2, 1000, 100000),
            'purchase_price'    => $this->faker->randomFloat(2, 1000, 100000),
            'sale_price'        => $this->faker->randomFloat(2, 1000, 100000),
            'promotional_price' => $this->faker->randomFloat(2, 1000, 100000),
            'vehicle_type_id'   => $this->faker->numberBetween(1, 2),
            'vehicle_model_id'  => $this->faker->numberBetween(1, 10),
            'supplier_id'       => $this->faker->numberBetween(1, 10),
            'year_one'          => $this->faker->year(),
            'year_two'          => $this->faker->year(),
            'km'                => $this->faker->numberBetween(0, 100000),
            'fuel'              => $this->faker->randomElement(['Gasoline', 'Ethanol', 'Diesel', 'Flex']),
            'engine_power'      => $this->faker->randomElement(['1.0', '1.4', '1.6', '1.8', '2.0']),
            'steering'          => $this->faker->randomElement(['Mechanical', 'Hydraulic', 'Electric']),
            'transmission'      => $this->faker->randomElement(['Manual', 'Automatic']),
            'doors'             => $this->faker->randomElement(['2', '4', '5']),
            'seats'             => $this->faker->randomElement(['2', '4', '5', '7', '9']),
            'traction'          => $this->faker->randomElement(['2x2', '4x2', '4x4']),
            'color'             => $this->faker->colorName(),
            'plate'             => $this->faker->regexify('[A-Z]{3}-[0-9]{4}'),
            'chassi'            => $this->faker->regexify('[A-Z0-9]{17}'),
            'renavam'           => $this->faker->regexify('[0-9]{11}'),
            'description'       => $this->faker->text(),
            'annotation'        => $this->faker->text(),
        ];
    }
}
