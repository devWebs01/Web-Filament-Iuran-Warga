<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\House>
 */
class HouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'house_number' => fake()->unique()->numberBetween(1, 999),
            'status' => fake()->randomElement(['dihuni', 'tidak_dihuni']),
        ];
    }
}
