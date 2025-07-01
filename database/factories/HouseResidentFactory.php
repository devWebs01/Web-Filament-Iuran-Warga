<?php

namespace Database\Factories;

use App\Models\House;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HouseResident>
 */
class HouseResidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomDate = fake()->date();

        return [
            'house_id' => House::inRandomOrder()->first()->id,
            'user_id' => User::where('role', 'warga')->inRandomOrder()->first()->id,
            'start_date' => $randomDate,
            'end_date' => Carbon::parse($randomDate)->addMonths(rand(1, 10))->format('Y-m-d'),        ];
    }
}
