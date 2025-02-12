<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Torneo;
use App\Models\JugadorFemenino;
use App\Models\Partida;
use App\Services\TorneoService;
use App\Services\Strategies\GanadorPorHabilidad;
use Illuminate\Support\Facades\DB;

class TorneoFemeninoSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            $ganadorStrategy = new GanadorPorHabilidad();
            $torneoService = new TorneoService($ganadorStrategy);

            // 1️⃣ Crear un torneo femenino
            $torneo = Torneo::create([
                'nombre' => 'Torneo de Prueba Femenino',
                'tipo' => 'Femenino',
                'fecha' => now()
            ]);

            // 2️⃣ Crear jugadoras femeninas y registrarlas en el torneo
            $jugadoras = [];
            for ($i = 1; $i <= 8; $i++) {
                $jugadora = DB::table('jugadores')->insertGetId([
                    'nombre' => "JugadorF$i",
                    'genero' => 'Femenino',
                    'habilidad' => rand(0, 100),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                JugadorFemenino::create([
                    'id' => $jugadora,
                    'reaccion' => rand(50, 100)
                ]);

                // Registrar en el torneo
                DB::table('torneo_jugador')->insert([
                    'torneo_id' => $torneo->id,
                    'jugador_id' => $jugadora,
                ]);

                $jugadoras[] = $jugadora;
            }

            // 3️⃣ Simular rondas eliminatorias
            while (count($jugadoras) > 1) {
                $nueva_ronda = [];

                for ($i = 0; $i < count($jugadoras); $i += 2) {
                    if (!isset($jugadoras[$i + 1])) {
                        $nueva_ronda[] = $jugadoras[$i]; // Avanza automáticamente si es impar
                        continue;
                    }


                    // Crear partida usando `TorneoService`
                    $partida = Partida::create([
                        'torneo_id' => $torneo->id,
                        'jugador1_id' => $jugadoras[$i],
                        'jugador2_id' => $jugadoras[$i + 1],
                        'ronda' => log(count($jugadoras), 2) // Calcula la ronda
                    ]);

                    // Determinar ganador (según habilidad)
                    $jugadora1 = DB::table('jugadores')->where('id', $jugadoras[$i])->first();
                    $jugadora2 = DB::table('jugadores')->where('id', $jugadoras[$i + 1])->first();

                    // Determinar ganadora
                    $ganadora_id = ($jugadora1->habilidad >= $jugadora2->habilidad) ? $jugadora1->id : $jugadora2->id;
                    $partida->update(['ganador_id' => $ganadora_id]);

                    $nueva_ronda[] = $ganadora_id;
                }

                $jugadoras = $nueva_ronda;
            }

            // 4️⃣ Finalizar torneo
            $torneo->update([
                'ganador_id' => $jugadoras[0],
            ]);
        });
    }
}
