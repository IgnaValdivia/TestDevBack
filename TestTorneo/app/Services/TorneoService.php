<?php

namespace App\Services;

use App\Models\Torneo;
use App\Models\Partida;
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
            $jugadores = $torneo->jugadores()->get()->shuffle();
            return $this->jugarPartidas($torneo, $jugadores);
        });
    }

    public function determinarGanador(Partida $partida)
    {
        return $this->ganadorStrategy->determinarGanador(
            $partida->jugador1,
            $partida->jugador2
        );
    }

    private function jugarPartidas(Torneo $torneo, $jugadores): Torneo
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

    public function totalPartidasJugadas(Torneo $torneo): int
    {
        return $torneo->partidas()->count();
    }
}
