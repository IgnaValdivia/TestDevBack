<?php

namespace App\Services;

use App\Interfaces\Repositories\IPartidaRepository;
use App\Interfaces\Repositories\ITorneoRepository;
use App\Models\Jugador;
use App\Models\Torneo;
use App\Models\Partida;
use App\Interfaces\ITorneoService;
use App\Interfaces\IGanadorStrategy;
use App\Interfaces\IJugadorService;
use Exception;
use Illuminate\Support\Facades\DB;

class TorneoService implements ITorneoService
{
    private ITorneoRepository $torneoRepository;
    private IPartidaRepository $partidaRepository;
    private IJugadorService $jugadorService;

    public function __construct(
        ITorneoRepository $torneoRepository,
        IPartidaRepository $partidaRepository,
        IJugadorService $jugadorService,
    ) {
        $this->torneoRepository = $torneoRepository;
        $this->partidaRepository = $partidaRepository;
        $this->jugadorService = $jugadorService;
    }

    public function getAll()
    {
        return $this->torneoRepository->getAll();
    }

    public function create(array $data): Torneo
    {
        //VALIDAR
        return $this->torneoRepository->create($data);
    }

    public function delete(int $id): bool
    {
        //Validar que exista el torneo

        $torneo = $this->torneoRepository->findById($id);

        if (!$torneo) {
            return false;
        }

        // Eliminar partidas del torneo antes de eliminar el torneo
        $this->partidaRepository->deleteByTorneoId($id);

        return $this->torneoRepository->delete($id);
    }

    public function restore(int $id): bool
    {
        $jugador = $this->torneoRepository->findByIdWithTrashed($id);

        if (!$jugador) {
            throw new Exception("Torneo no encontrado o no está eliminado.");
        }

        return $this->torneoRepository->restore($id);
    }

    public function determinarGanador(Partida $partida)
    {

        $ganador = $this->jugadorService->calcularPuntaje($partida->jugador1) >= $this->jugadorService->calcularPuntaje($partida->jugador2) ? $partida->jugador1 : $partida->jugador2;

        $this->partidaRepository->update($partida->id, ['ganador_id' => $ganador->id]);

        return $ganador;
    }

    public function actualizarGanador(int $torneoId, int $ganadorId): bool
    {
        $torneo = $this->torneoRepository->findById($torneoId);

        if (!$torneo) {
            throw new Exception("Torneo no encontrado.");
        }

        // Actualizamos el torneo con el ganador
        return $this->torneoRepository->update($torneoId, ['ganador_id' => $ganadorId]);
    }
}
