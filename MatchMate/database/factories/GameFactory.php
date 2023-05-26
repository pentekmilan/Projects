<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-5 day', '+10 day')->format('Y-m-d');
        $finished = $start < date('Y-m-d');
        return [
            'start' => $start,
            'finished' => $finished,
        ];
    }
}
