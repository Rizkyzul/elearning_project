<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Dosen;     // Import Dosen
use App\Models\Mahasiswa; // Import Mahasiswa

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Password default untuk semua seeder.
     *
     * @var string
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'), // password defaultnya 'password'
            'remember_token' => Str::random(10),

            // --- Edit Default Bawaan Breeze ---
            'role' => 'mahasiswa', // Default jika tidak dispesifikkan
            'must_change_password' => false, // Kita set false agar mudah testing
        ];
    }

    /**
     * State untuk membuat user Dosen.
     */
    public function dosen(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'dosen',
        ])
        // Hook 'afterCreating' akan dijalankan SETELAH user dibuat
        ->afterCreating(function (User $user) {
            // Buatkan profil Dosen untuk user ini
            Dosen::factory()->create(['user_id' => $user->id]);
        });
    }

    /**
     * State untuk membuat user Mahasiswa.
     */
    public function mahasiswa(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'mahasiswa',
        ])
        ->afterCreating(function (User $user) {
            // Buatkan profil Mahasiswa untuk user ini
            Mahasiswa::factory()->create(['user_id' => $user->id]);
        });
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