<?php

namespace App\Services;

use App\DTOs\PartidaDTO;
use App\DTOs\TorneoDTO;
use App\Interfaces\Repositories\IPartidaRepository;
use App\Interfaces\Repositories\ITorneoRepository;
use App\Models\Torneo;
use App\Models\Partida;
use App\Interfaces\ITorneoService;
use App\Interfaces\IJugadorService;
use Exception;
use Illuminate\Database\Eloquent\Collection;

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

    public function getAll(): array
    {
        return $this->torneoRepository->getAll()->map(fn($torneo) => TorneoDTO::fromModel($torneo))
            ->toArray();
    }

    public function findById(int $id): ?TorneoDTO
    {
        $torneo = $this->torneoRepository->findById($id);

        if (!$torneo) {
            return null;
        }

        return TorneoDTO::fromModel($torneo);
    }

    public function create(array $data): TorneoDTO
    {
        $data['estado'] = 'Pendiente';
        $torneo = $this->torneoRepository->create($data);
        return TorneoDTO::fromModel($torneo);
    }

    public function update(int $id, array $data): bool
    {
        $torneo = $this->torneoRepository->findById($id);

        if (!$torneo) {
            throw new Exception("Jugador no encontrado");
        }

        return $this->torneoRepository->update($id, [
            'nombre' => $data['nombre'] ?? $torneo->nombre,
            'tipo' => $data['tipo'] ?? $torneo->tipo,
            'fecha' => $data['fecha'] ?? $torneo->fecha,
        ]);
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
        return $this->torneoRepository->update($torneoId, ['ganador_id' => $ganadorId, 'estado' => 'Finalizado']);
    }

    public function getPartidas(int $id): array
    {
        return $this->torneoRepository->getPartidas($id)->map(fn($partida) => PartidaDTO::fromModel($partida))
            ->toArray();;
    }

    public function asignarJugadores(int $id, array $jugadores): array|string|bool|null
    {
        $torneo = $this->torneoRepository->findById($id);

        if (!$torneo) {
            return null; // Torneo no encontrado
        }

        // Obtener los jugadores existentes en la BD
        $jugadoresValidos = $this->jugadorService->findByIds($jugadores);

        // Obtener los jugadores que no existen comparando los IDs enviados con los encontrados en la BD
        $jugadoresNoExistentes = array_diff($jugadores, $jugadoresValidos->pluck('id')->toArray());

        if (!empty($jugadoresNoExistentes)) {
            return ['error' => 'no_existe', 'jugadores' => array_values($jugadoresNoExistentes)];
        }

        // Validar que todos los jugadores sean del mismo género que el torneo
        $jugadoresGeneroIncorrecto = [];

        foreach ($jugadoresValidos as $jugador) {
            if ($jugador->genero !== $torneo->tipo) {
                $jugadoresGeneroIncorrecto[] = $jugador->id;
            }
        }

        if (!empty($jugadoresGeneroIncorrecto)) {
            return ['error' => 'genero_invalido', 'jugadores' => $jugadoresGeneroIncorrecto];
        }

        $this->torneoRepository->asignarJugadores($id, $jugadores);
        return true; // Asignación exitosa
    }

    public function comenzarTorneo(int $id): bool
    {
        //logica de torneo
        return true;
    }

    public function getEstado(int $id): ?string
    {
        return $this->torneoRepository->getEstado($id);
    }

    public function getPartidasPorRonda(int $id, int $ronda): array
    {
        return $this->torneoRepository->getPartidasPorRonda($id, $ronda)->map(fn($partida) => PartidaDTO::fromModel($partida))
            ->toArray();
    }
}
