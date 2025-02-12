<?php

namespace App\Services;

use App\Interfaces\Repositories\IPartidaRepository;
use App\Interfaces\Repositories\ITorneoRepository;
use App\Models\Jugador;
use App\Models\Torneo;
use App\Models\Partida;
use App\Interfaces\ITorneoService;
use App\Interfaces\IGanadorStrategy;
use Illuminate\Support\Facades\DB;

class TorneoService implements ITorneoService
{
    private ITorneoRepository $torneoRepository;
    private IPartidaRepository $partidaRepository;
    private IGanadorStrategy $ganadorStrategy;

    public function __construct(
        ITorneoRepository $torneoRepository,
        IPartidaRepository $partidaRepository,
        IGanadorStrategy $ganadorStrategy
    ) {
        $this->torneoRepository = $torneoRepository;
        $this->partidaRepository = $partidaRepository;
        $this->ganadorStrategy = $ganadorStrategy;
    }


    public function crearPartida(Torneo $torneo, Jugador $jugador1, Jugador $jugador2, int $ronda): Partida
    {
        return $this->partidaRepository->create([
            'torneo_id' => $torneo->id,
            'jugador1_id' => $jugador1->id,
            'jugador1_type' => get_class($jugador1),
            'jugador2_id' => $jugador2->id,
            'jugador2_type' => get_class($jugador2),
            'ronda' => $ronda
        ]);
    }

    public function obtenerTorneos()
    {
        return $this->torneoRepository->getAll();
    }

    public function crearTorneo(array $data): Torneo
    {
        return $this->torneoRepository->create($data);
    }

    public function eliminarTorneo(int $id): bool
    {
        return $this->torneoRepository->delete($id);
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

        $this->partidaRepository->update($partida->id, ['ganador_id' => $ganador->id]);

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
