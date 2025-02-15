<?php

namespace Database\Seeders;

use App\Interfaces\IJugadorFemeninoService;
use App\Interfaces\Repositories\IPartidaRepository;
use Illuminate\Database\Seeder;
use App\Services\TorneoService;
use Illuminate\Support\Facades\DB;

class TorneoFemeninoSeeder extends Seeder
{
    private TorneoService $torneoService;
    private IJugadorFemeninoService $jugadorService;
    private IPartidaRepository $partidaRepository;

    public function __construct(TorneoService $torneoService, IJugadorFemeninoService $jugadorService, IPartidaRepository $partidaRepository)
    {
        $this->torneoService = $torneoService;
        $this->jugadorService = $jugadorService;
        $this->partidaRepository = $partidaRepository;
    }

    public function run()
    {

        DB::transaction(function () {

            //Crear un torneo femenino
            $torneo = $this->torneoService->create([
                'nombre' => 'Torneo de Prueba Femenino',
                'tipo' => 'Femenino',
                'fecha' => now()
            ]);


            //Crear jugadores femeninos
            $jugadores = collect();
            for ($i = 1; $i <= 8; $i++) { // 8 jugadores
                $jugador = $this->jugadorService->create([
                    'nombre' => "JugadorM$i",
                    'dni' => rand(10000000, 99999999),
                    'habilidad' => rand(0, 100),
                    'reaccion' => rand(0, 100),
                ]);
                $jugadores->push($jugador);
            }

            //Agregar jugadores al torneo
            foreach ($jugadores as $jugador) {
                $torneo->jugadores()->attach($jugador->id);
            }


            //Simular las rondas del torneo (Cuartos, Semifinal, Final)
            $ronda = 1;
            while ($jugadores->count() > 1) {
                $ganadores = collect();
                $jugadores = $jugadores->shuffle(); // Mezclar jugadores

                for ($i = 0; $i < $jugadores->count(); $i += 2) {
                    if (!isset($jugadores[$i + 1])) break; // Evitar errores si es impar

                    $jugador1 = $jugadores[$i];
                    $jugador2 = $jugadores[$i + 1];

                    //Crear la partida
                    $partida = $this->partidaRepository->create([
                        'torneo_id' => $torneo->id,
                        'jugador1_id' => $jugador1->id,
                        'jugador1_type' => 'App\Models\JugadorFemenino',
                        'jugador2_id' => $jugador2->id,
                        'jugador2_type' => 'App\Models\JugadorFemenino',
                        'ronda' => $ronda
                    ]);

                    //Determinar el ganador usando la estrategia
                    $ganador = $this->torneoService->determinarGanador($partida);

                    //Guardar al ganador para la siguiente ronda
                    $ganadores->push($ganador);
                }

                $jugadores = $ganadores;
                $ronda++;
            }

            //Anunciar al ganador final
            $ganadorFinal = $jugadores->first();
            $this->torneoService->actualizarGanador($torneo->id, $ganadorFinal->id);

            echo "El ganador del Torneo Femenino es: " . $ganadorFinal->jugador->nombre . "\n";
        });
    }
}
