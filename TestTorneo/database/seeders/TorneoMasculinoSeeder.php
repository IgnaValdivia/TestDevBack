<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Torneo;
use App\Models\JugadorMasculino;
use App\Models\Partida;
use App\Repositories\PartidaRepository;
use App\Repositories\TorneoRepository;
use App\Services\Strategies\GanadorPorHabilidad;
use App\Services\TorneoService;
use Illuminate\Support\Facades\DB;

class TorneoMasculinoSeeder extends Seeder
{
    public function run()
    {


        DB::transaction(function () {
            $torneoRepository = new TorneoRepository();
            $partidaRepository = new PartidaRepository();
            $ganadorStrategy = new GanadorPorHabilidad();
            $torneoService = new TorneoService($torneoRepository, $partidaRepository, $ganadorStrategy);


            //Crear un torneo masculino
            $torneo = $torneoService->crearTorneo([
                'nombre' => 'Torneo de Prueba Masculino',
                'tipo' => 'Masculino',
                'fecha' => now()
            ]);
        });
    }
}
