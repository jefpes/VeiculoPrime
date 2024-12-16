<?php

namespace Database\Factories;

use App\Enums\{Genders, MaritalStatus};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\People>
 */
class PeopleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender     = $this->faker->randomElement(array_map(fn ($case) => $case->value, Genders::cases()));
        $clientType = $this->faker->randomElement(['Física', 'Jurídica']);

        return [
            'name'           => $gender === 'MASCULINO' ? $this->faker->name('male') : $this->faker->name('female'),
            'gender'         => $gender,
            'email'          => fake()->unique()->safeEmail(),
            'salary'         => $this->faker->randomFloat(2, 1000, 10000),
            'rg'             => $this->faker->unique()->numerify('##.###.###-#'),
            'person_type'    => $clientType, // Define o tipo de contribuinte
            'person_id'      => $clientType === 'Física' ? $this->faker->unique()->numerify('###.###.###-##') : $this->faker->unique()->numerify('##.###.###/####-##'), // Gera CNPJ
            'marital_status' => $this->faker->randomElement(array_map(fn ($case) => $case->value, MaritalStatus::cases())),
            'birthday'       => $this->faker->date(),
            'father'         => $this->faker->optional()->name('male'),
            'mother'         => $this->faker->optional()->name('female'),
            'spouse'         => $this->faker->optional()->name,
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

    public function withAffiliate(int $count = 1)
    {
        return $this->afterCreating(function (Model $model) use ($count) {
            for ($i = 0; $i < $count; $i++) {
                $model->affiliates()->create(array_merge(
                    AffiliateFactory::new()->definition()
                ));
            }
        });
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

    public function withClient()
    {
        return $this->afterCreating(function (Model $model) {
            $model->client()->create(['active' => true]);
        });
    }

    public function withSupplier()
    {
        return $this->afterCreating(function (Model $model) {
            $model->supplier()->create(['active' => true]);
        });
    }

    public function withEmployee()
    {
        return $this->afterCreating(function (Model $model) {
            $model->employee()->create([
                'salary'           => $this->faker->randomFloat(2, 1000, 10000),
                'admission_date'   => $this->faker->date(),
                'resignation_date' => $this->faker->optional()->date(),
            ]);
        });
    }
}
