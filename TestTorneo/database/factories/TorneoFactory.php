<?php

namespace Database\Factories;

use App\Models\Jugador;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Torneo>
 */
class TorneoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->sentence(3),
            'tipo' => $this->faker->randomElement(['Masculino', 'Femenino']),
            'fecha' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'ganador_id' => Jugador::factory()->create(['genero' => 'Masculino'])->id,
        ];
    }
}
