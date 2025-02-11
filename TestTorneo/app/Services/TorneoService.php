<?php

namespace App\Services;

use App\Models\Torneo;
use App\Models\Partida;
use App\Models\Jugador;
use App\Services\Interfaces\ITorneoService;
use App\Services\Interfaces\IGanadorStrategy;
use Illuminate\Support\Facades\DB;

class TorneoService implements ITorneoService
{
    private IGanadorStrategy $ganadorStrategy;

    public function __construct(IGanadorStrategy $ganadorStrategy)
    {
        $this->ganadorStrategy = $ganadorStrategy;
    }

    public function comenzarTorneo(int $torneoId): ?Torneo
    {
        return DB::transaction(function () use ($torneoId) {
            $torneo = Torneo::with('jugadores')->find($torneoId);
            if (!$torneo || $torneo->estado === 'Finalizado') {
                return null;
            }

            $jugadores = $torneo->jugadores()->get()->shuffle();
            if (!$this->esPotenciaDeDos($jugadores->count())) {
                return null;
            }

            return $this->ejecutarEliminacionDirecta($torneo, $jugadores);
        });
    }

    private function esPotenciaDeDos(int $num): bool
    {
        return ($num & ($num - 1)) === 0 && $num > 0;
    }

    private function ejecutarEliminacionDirecta(Torneo $torneo, $jugadores): Torneo
    {
        $ronda = 1;
        while ($jugadores->count() > 1) {
            $nuevaRonda = collect();
            for ($i = 0; $i < $jugadores->count(); $i += 2) {
                $partida = Partida::create([
                    'jugador1_id' => $jugadores[$i]->id,
                    'jugador2_id' => $jugadores[$i + 1]->id,
                    'ganador_id' => null,
                    'ronda' => $ronda,
                    'torneo_id' => $torneo->id
                ]);

                $ganador = $this->ganadorStrategy->determinarGanador($jugadores[$i], $jugadores[$i + 1]);
                $partida->update(['ganador_id' => $ganador->id]);
                $nuevaRonda->push($ganador);
            }
            $jugadores = $nuevaRonda;
            $ronda++;
        }

        $torneo->update(['ganador_id' => $jugadores->first()->id]);
        return $torneo;
    }
}
