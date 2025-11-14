<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
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
            // 'kelas_id' akan kita isi manual di Seeder
            'nama' => fake()->name(),
            'nim' => fake()->unique()->numerify('20221###'),
            'angkatan' => '2022',
            'prodi' => 'Teknik Informatika',
        ];
    }
}
