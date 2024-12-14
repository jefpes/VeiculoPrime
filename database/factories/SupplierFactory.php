<?php

namespace Database\Factories;

use App\Enums\{Genders, MaritalStatus};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Suppliers>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Gera o tipo de contribuinte
        $supplierType = $this->faker->randomElement(['Física', 'Jurídica']);
        $gender       = $supplierType === 'Física' ? $this->faker->randomElement(array_map(fn ($case) => $case->value, Genders::cases())) : Genders::OUTRO->value;

        return [
            'name'           => $gender === 'MASCULINO' ? $this->faker->name('male') : $this->faker->name('female'),
            'gender'         => $gender,
            'supplier_type'  => $supplierType, // Define o tipo de contribuinte
            'supplier_id'    => $supplierType === 'Física' ? $this->faker->unique()->numerify('###.###.###-##') : $this->faker->unique()->numerify('##.###.###/####-##'), // Gera CNPJ
            'rg'             => $supplierType === 'Física' ? $this->faker->unique()->numerify('##########-#') : null,
            'marital_status' => $this->faker->randomElement(array_map(fn ($case) => $case->value, MaritalStatus::cases())),
            'spouse'         => $this->faker->optional()->name,
            'birth_date'     => $this->faker->date(),
            'father'         => $this->faker->optional()->name('male'),
            'mother'         => $this->faker->optional()->name('female'),
            'description'    => $this->faker->text,
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
}
