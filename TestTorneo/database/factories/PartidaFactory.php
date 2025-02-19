<?php

namespace Database\Factories;

use App\Models\Jugador;
use App\Models\JugadorFemenino;
use App\Models\JugadorMasculino;
use App\Models\Partida;
use App\Models\Torneo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Partida>
 */
class PartidaFactory extends Factory
{
    protected $model = Partida::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Decidir si la partida es de torneo Masculino o Femenino
        $tipoTorneo = $this->faker->randomElement(['Masculino', 'Femenino']);

        if ($tipoTorneo === 'Masculino') {
            $jugador1 = Jugador::factory()->create(['genero' => 'Masculino']);
            $jugador2 = Jugador::factory()->create(['genero' => 'Masculino']);
        } else {
            $jugador1 = Jugador::factory()->create(['genero' => 'Femenino']);
            $jugador2 = Jugador::factory()->create(['genero' => 'Femenino']);
        }

        return [
            'torneo_id' => Torneo::factory()->create(['tipo' => $tipoTorneo]), // Crear un torneo del mismo tipo
            'jugador1_id' => $jugador1->id,
            'jugador2_id' => $jugador2->id,
            'ronda' => $this->faker->numberBetween(1, 5),
        ];
    }
}
