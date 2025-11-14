<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dosen>
 */
class DosenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'user_id' akan diisi oleh UserFactory
            'nama' => fake()->name(),
            'nidn' => fake()->unique()->numerify('100#####'),
            'prodi' => 'Teknik Informatika',
        ];
    }
}
