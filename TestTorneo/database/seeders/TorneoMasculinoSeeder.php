<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Torneo;
use App\Models\JugadorMasculino;
use App\Models\Partida;
use App\Services\Strategies\GanadorPorHabilidad;
use App\Services\TorneoService;
use Illuminate\Support\Facades\DB;

class TorneoMasculinoSeeder extends Seeder
{
    public function run()
    {


        DB::transaction(function () {
            $ganadorStrategy = new GanadorPorHabilidad();
            $torneoService = new TorneoService($ganadorStrategy);

            // 1️⃣ Crear un torneo masculino
            $torneo = Torneo::create([
                'nombre' => 'Torneo de Prueba Masculino',
                'tipo' => 'Masculino',
                'fecha' => now()
            ]);

            // 2️⃣ Crear jugadores masculinos y registrarlos en el torneo
            $jugadores = [];
            for ($i = 1; $i <= 8; $i++) {
                $jugador = DB::table('jugadores')->insertGetId([
                    'nombre' => "JugadorM$i",
                    'genero' => 'Masculino',
                    'habilidad' => rand(0, 100),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                JugadorMasculino::create([
                    'id' => $jugador,
                    'fuerza' => rand(0, 100),
                    'velocidad' => rand(0, 100)
                ]);

                // Registrar jugador en torneo
                DB::table('torneo_jugador')->insert([
                    'torneo_id' => $torneo->id,
                    'jugador_id' => $jugador,
                ]);

                $jugadores[] = $jugador;
            }

            // 3️⃣ Simular rondas del torneo
            while (count($jugadores) > 1) {
                $nueva_ronda = [];

                for ($i = 0; $i < count($jugadores); $i += 2) {
                    if (!isset($jugadores[$i + 1])) {
                        $nueva_ronda[] = $jugadores[$i]; // Si es impar, avanza automáticamente
                        continue;
                    }

                    // Crear partida
                    /*$partida = Partida::create([
                        'torneo_id' => $torneo->id,
                        'jugador1_id' => $jugadores[$i],
                        'jugador2_id' => $jugadores[$i + 1],
                        'ronda' => log(count($jugadores), 2) // Calcula la ronda
                    ]);*/


                    $partida = $torneoService->crearPartida($torneo, $jugadores[$i], $jugadores[$i + 1], $i);

                    $ganador = $torneoService->determinarGanador($partida);

                    // Avanza a la siguiente ronda
                    $nueva_ronda[] = $ganador->id;
                }

                $jugadores = $nueva_ronda;
            }

            // 4️⃣ Finalizar el torneo y asignar el ganador
            $torneo->update([
                'ganador_id' => $jugadores[0]
            ]);
        });
    }
}
