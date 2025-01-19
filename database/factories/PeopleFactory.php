<?php

namespace Database\Factories;

use App\Enums\{MaritalStatus, Sexes};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

class PeopleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sex        = $this->faker->randomElement(Sexes::class);
        $clientType = $this->faker->randomElement(['Física', 'Jurídica']);

        return [
            'name'           => $sex == 'Masculino' ? $this->faker->name('male') : $this->faker->name('female'),
            'sex'            => $sex,
            'email'          => fake()->unique()->safeEmail(),
            'rg'             => $clientType == 'Física' ? $this->faker->unique()->numerify('##.###.###-#') : null,
            'person_type'    => $clientType,
            'person_id'      => $clientType == 'Física' ? $this->faker->unique()->numerify('###.###.###-##') : $this->faker->unique()->numerify('##.###.###/####-##'),
            'marital_status' => $clientType == 'Física' ? $this->faker->randomElement(MaritalStatus::class) : null,
            'birthday'       => $clientType == 'Física' ? $this->faker->date() : null,
            'father'         => $clientType == 'Física' ? $this->faker->optional()->name('male') : null,
            'mother'         => $clientType == 'Física' ? $this->faker->optional()->name('female') : null,
            'spouse'         => $clientType == 'Física' ? $this->faker->optional()->name : null,
            'client'         => $this->faker->boolean,
            'supplier'       => $this->faker->boolean,
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
