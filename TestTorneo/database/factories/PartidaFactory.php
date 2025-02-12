<?php

namespace Database\Factories;

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
            $jugador1 = JugadorMasculino::factory()->create();
            $jugador2 = JugadorMasculino::factory()->create();
        } else {
            $jugador1 = JugadorFemenino::factory()->create();
            $jugador2 = JugadorFemenino::factory()->create();
        }

        return [
            'torneo_id' => Torneo::factory()->create(['tipo' => $tipoTorneo]), // Crear un torneo del mismo tipo
            'jugador1_id' => $jugador1->id,
            'jugador1_type' => get_class($jugador1),
            'jugador2_id' => $jugador2->id,
            'jugador2_type' => get_class($jugador2),
            'ronda' => $this->faker->numberBetween(1, 5),
        ];
    }
}
