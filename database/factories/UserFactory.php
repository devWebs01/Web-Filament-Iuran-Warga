<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $imageUrl = 'https://picsum.photos/400/200';

        $imageContents = Http::get($imageUrl);
        $imageName = basename(fake()->sentence(1).$imageUrl.'.png');
        $storagePath = 'ktp/'.$imageName;

        // Simpan gambar ke folder public storage
        Storage::disk('public')->put($storagePath, $imageContents);

        // Simpan path gambar ke database
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'ktp_photo' => $storagePath,
            'status' => fake()->randomElement(['tetap', 'kontrak']),
            'role' => fake()->randomElement(['bendahara', 'warga']),
            'phone_number' => fake()->phoneNumber(),
            'is_married' => fake()->randomElement([true, false]),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
