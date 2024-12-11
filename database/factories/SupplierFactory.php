<?php

namespace Database\Factories;

use App\Enums\{Genders, MaritalStatus};
use App\Models\{Supplier};
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'name'                 => $gender === 'MASCULINO' ? $this->faker->name('male') : $this->faker->name('female'),
            'gender'               => $gender,
            'supplier_type'        => $supplierType, // Define o tipo de contribuinte
            'supplier_id'          => $supplierType === 'Física' ? $this->faker->unique()->numerify('###.###.###-##') : $this->faker->unique()->numerify('##.###.###/####-##'), // Gera CNPJ
            'rg'                   => $supplierType === 'Física' ? $this->faker->unique()->numerify('##########-#') : null,
            'marital_status'       => $this->faker->randomElement(array_map(fn ($case) => $case->value, MaritalStatus::cases())),
            'spouse'               => $this->faker->optional()->name,
            'phone_one'            => $this->faker->unique()->numerify('(##) #####-####'),
            'phone_two'            => $this->faker->optional()->numerify('(##) #####-####'),
            'birth_date'           => $this->faker->date(),
            'father'               => $this->faker->optional()->name('male'),
            'father_phone'         => $this->faker->optional()->numerify('(##) #####-####'),
            'mother'               => $this->faker->name('female'),
            'mother_phone'         => $this->faker->numerify('(##) #####-####'),
            'affiliated_one'       => $this->faker->name,
            'affiliated_one_phone' => $this->faker->numerify('(##) #####-####'),
            'affiliated_two'       => $this->faker->name,
            'affiliated_two_phone' => $this->faker->numerify('(##) #####-####'),
            'description'          => $this->faker->text,
        ];
    }

    public function withAddress()
    {
        return $this->afterCreating(function (Supplier $supplier) {
            // Cria um endereço usando o AddressFactory e associa ao Client
            $supplier->addresses()->create(array_merge(
                AddressFactory::new()->definition()
            ));
        });
    }
}
