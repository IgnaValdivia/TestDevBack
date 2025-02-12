<?php

namespace Database\Factories;

use App\Models\Jugador;
use App\Models\JugadorFemenino;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JugadorFemenino>
 */
class JugadorFemeninoFactory extends Factory
{
    protected $model = JugadorFemenino::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Insertar primero el jugador base en `jugadores`
        $jugadorId = DB::table('jugadores')->insertGetId([
            'nombre' => $this->faker->name(),
            'genero' => 'Femenino',
            'habilidad' => $this->faker->numberBetween(0, 100),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return [
            'id' => $jugadorId,
            'reaccion' => $this->faker->numberBetween(0, 100),
        ];
    }
}
