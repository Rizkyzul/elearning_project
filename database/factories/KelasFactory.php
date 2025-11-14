<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_kelas' => 'TI-' . fake()->randomElement(['KIP-C1', 'KIP-C2', 'KIP-C3', 'LEGULAR-P1']),
        ];
    }
}
