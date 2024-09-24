<?php

namespace Database\Factories;

use App\Enums\{Genders, MaritalStatus};
use App\Models\{Client, ClientAddress};
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
        $taxpayerType = $this->faker->randomElement(['Física', 'Jurídica']);
        $gender       = $taxpayerType === 'Física' ? $this->faker->randomElement(array_map(fn ($case) => $case->value, Genders::cases())) : Genders::OUTRO->value;

        return [
            'name'                 => $gender === 'MASCULINO' ? $this->faker->name('male') : $this->faker->name('female'),
            'gender'               => $gender,
            'taxpayer_type'        => $taxpayerType, // Define o tipo de contribuinte
            'taxpayer_id'          => $taxpayerType === 'Física' ? $this->faker->unique()->numerify('###.###.###-##') : $this->faker->unique()->numerify('##.###.###/####-##'), // Gera CNPJ
            'rg'                   => $taxpayerType === 'Física' ? $this->faker->unique()->numerify('##########-#') : null,
            'marital_status'       => $taxpayerType === 'Física' ? $this->faker->randomElement(array_map(fn ($case) => $case->value, MaritalStatus::cases())) : null,
            'phone_one'            => $this->faker->unique()->numerify('(##) #####-####'),
            'phone_two'            => $this->faker->optional()->numerify('(##) #####-####'),
            'birth_date'           => $taxpayerType === 'Física' ? $this->faker->date() : null,
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

    /**
     * Create a client with an address.
     *
     * @return static
     */
    public function withAddress()
    {
        return $this->afterCreating(function (Client $client) {
            ClientAddress::factory()->create(['client_id' => $client->id]);
        });
    }
}
