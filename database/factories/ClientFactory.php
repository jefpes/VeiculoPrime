<?php

namespace Database\Factories;

use App\Enums\{Genders, MaritalStatus};
use App\Models\{Client};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Gera o tipo de contribuinte
        $clientType = $this->faker->randomElement(['Física', 'Jurídica']);
        $gender     = $clientType === 'Física' ? $this->faker->randomElement(array_map(fn ($case) => $case->value, Genders::cases())) : Genders::OUTRO->value;

        return [
            'name'           => $gender === 'MASCULINO' ? $this->faker->name('male') : $this->faker->name('female'),
            'gender'         => $gender,
            'client_type'    => $clientType, // Define o tipo de contribuinte
            'client_id'      => $clientType === 'Física' ? $this->faker->unique()->numerify('###.###.###-##') : $this->faker->unique()->numerify('##.###.###/####-##'), // Gera CNPJ
            'rg'             => $clientType === 'Física' ? $this->faker->unique()->numerify('##########-#') : null,
            'marital_status' => $clientType === 'Física' ? $this->faker->randomElement(array_map(fn ($case) => $case->value, MaritalStatus::cases())) : null,
            'spouse'         => $this->faker->optional()->name,
            'phone_one'      => $this->faker->unique()->numerify('(##) #####-####'),
            'phone_two'      => $this->faker->optional()->numerify('(##) #####-####'),
            'birth_date'     => $clientType === 'Física' ? $this->faker->date() : null,
            'description'    => $this->faker->text,
        ];
    }

    public function withAddress()
    {
        return $this->afterCreating(function (Client $client) {
            $client->addresses()->create(array_merge(
                AddressFactory::new()->definition()
            ));
        });
    }

    public function withAffiliate()
    {
        return $this->afterCreating(function (Client $client) {
            $client->affiliates()->create(array_merge(
                AffiliateFactory::new()->definition()
            ));
        });
    }
}
