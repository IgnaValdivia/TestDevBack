<?php

namespace App\Services;

use App\Models\Jugador;
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

    public function crearPartida(Torneo $torneo, int $jugador1, int $jugador2, int $ronda): Partida
    {
        return Partida::create([
            'torneo_id' => $torneo->id,
            'jugador1_id' => $jugador1,
            'jugador2_id' => $jugador2,
            'ronda' => $ronda
        ]);
    }

    public function comenzarTorneo(int $torneoId): ?Torneo
    {
        return new Torneo();
    }

    public function determinarGanador(Partida $partida)
    {
        $ganador = $this->ganadorStrategy->determinarGanador(
            $partida->jugador1,
            $partida->jugador2
        );

        $partida->update(['ganador_id' => $ganador->id]);

        return $ganador;
    }

    private function jugarPartidas(Torneo $torneo, $jugadores): Torneo
    {
        return new Torneo();
    }

    public function totalPartidasJugadas(Torneo $torneo): int
    {
        return $torneo->partidas()->count();
    }
}
