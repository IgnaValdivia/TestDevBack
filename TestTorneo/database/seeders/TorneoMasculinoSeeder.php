<?php

namespace Database\Seeders;

use App\Interfaces\IJugadorMasculinoService;
use App\Interfaces\Repositories\IPartidaRepository;
use Illuminate\Database\Seeder;
use App\Services\TorneoService;
use Illuminate\Support\Facades\DB;

class TorneoMasculinoSeeder extends Seeder
{
    private TorneoService $torneoService;
    private IJugadorMasculinoService $jugadorService;
    private IPartidaRepository $partidaRepository;

    public function __construct(TorneoService $torneoService, IJugadorMasculinoService $jugadorService, IPartidaRepository $partidaRepository)
    {
        $this->torneoService = $torneoService;
        $this->jugadorService = $jugadorService;
        $this->partidaRepository = $partidaRepository;
    }

    public function run()
    {

        DB::transaction(function () {

            //Crear un torneo masculino
            $torneo = $this->torneoService->create([
                'nombre' => 'Torneo de Prueba Masculino',
                'tipo' => 'Masculino',
                'fecha' => now()
            ]);


            //Crear jugadores masculinos
            $jugadores = collect();
            for ($i = 1; $i <= 8; $i++) { // 8 jugadores
                $jugador = $this->jugadorService->create([
                    'nombre' => "JugadorM$i",
                    'dni' => rand(10000000, 99999999),
                    'habilidad' => rand(0, 100),
                    'fuerza' => rand(0, 100),
                    'velocidad' => rand(0, 100)
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
                        'jugador1_type' => 'App\Models\JugadorMasculino',
                        'jugador2_id' => $jugador2->id,
                        'jugador2_type' => 'App\Models\JugadorMasculino',
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

            echo "El ganador del Torneo Masculino es: " . $ganadorFinal->jugador->nombre . "\n";
        });
    }
}
