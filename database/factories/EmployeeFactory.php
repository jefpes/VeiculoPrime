<?php

namespace Database\Factories;

use App\Enums\{Genders, MaritalStatus};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employees>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = $this->faker->randomElement(array_map(fn ($case) => $case->value, Genders::cases()));

        return [
            'name'             => $gender === 'MASCULINO' ? $this->faker->name('male') : $this->faker->name('female'),
            'gender'           => $gender,
            'email'            => fake()->unique()->safeEmail(),
            'salary'           => $this->faker->randomFloat(2, 1000, 10000),
            'rg'               => $this->faker->unique()->numerify('##.###.###-#'),
            'cpf'              => $this->faker->unique()->numerify('###.###.###-##'),
            'marital_status'   => $this->faker->randomElement(array_map(fn ($case) => $case->value, MaritalStatus::cases())),
            'phone_one'        => $this->faker->unique()->numerify('(##) #####-####'),
            'phone_two'        => $this->faker->optional()->numerify('(##) #####-####'),
            'birth_date'       => $this->faker->date(),
            'father'           => $this->faker->optional()->name('male'),
            'mother'           => $this->faker->name('female'),
            'spouse'           => $this->faker->optional()->name,
            'admission_date'   => $this->faker->date(),
            'resignation_date' => $this->faker->optional()->date(),
        ];
    }

    public function withAddress(int $count = 1)
    {
        return $this->afterCreating(function (Model $model) use ($count) {
            for ($i = 0; $i < $count; $i++) {
                $model->addresses()->create(array_merge(
                    AddressFactory::new()->definition()
                ));
            }
        });
    }
}
