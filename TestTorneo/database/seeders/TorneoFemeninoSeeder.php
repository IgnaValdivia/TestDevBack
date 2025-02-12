<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Torneo;
use App\Models\JugadorFemenino;
use App\Models\Partida;
use App\Repositories\PartidaRepository;
use App\Repositories\TorneoRepository;
use App\Services\TorneoService;
use App\Services\Strategies\GanadorPorHabilidad;
use Illuminate\Support\Facades\DB;

class TorneoFemeninoSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            $torneoRepository = new TorneoRepository();
            $partidaRepository = new PartidaRepository();
            $ganadorStrategy = new GanadorPorHabilidad();
            $torneoService = new TorneoService($torneoRepository, $partidaRepository, $ganadorStrategy);

            // Crear un torneo femenino
            $torneo = $torneoService->crearTorneo([
                'nombre' => 'Torneo de Prueba Femenino',
                'tipo' => 'Femenino',
                'fecha' => now()
            ]);
        });
    }
}
