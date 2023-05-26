<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->name;
        $number = $this->faker->numberBetween(1, 99);
        $birthdate = $this->faker->date('Y-m-d', 'now');
        return [
            'name' => $name,
            'number' => $number,
            'birthdate' => $birthdate,
        ];
    }
}
