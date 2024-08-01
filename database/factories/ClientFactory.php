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
        return [
            'name'                 => $this->faker->name,
            'gender'               => $this->faker->randomElement(array_map(fn ($case) => $case->value, Genders::cases())),
            'rg'                   => $this->faker->unique()->numerify('##.###.###-#'),
            'cpf'                  => $this->faker->unique()->numerify('###.###.###-##'),
            'marital_status'       => $this->faker->randomElement(array_map(fn ($case) => $case->value, MaritalStatus::cases())),
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
        return $this->afterCreating(function (Client $client) {
            ClientAddress::factory()->create(['client_id' => $client->id]);
        });
    }
}
