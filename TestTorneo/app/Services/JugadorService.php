<?php

namespace App\Services;

use App\DTOs\JugadorDTO;
use App\Interfaces\IJugadorService;
use App\Interfaces\Repositories\IJugadorRepository;
use App\Models\JugadorFemenino;
use App\Models\JugadorMasculino;
use Exception;
use InvalidArgumentException;

class JugadorService implements IJugadorService
{
    private IJugadorRepository $jugadorRepository;

    public function __construct(IJugadorRepository $jugadorRepository)
    {
        $this->jugadorRepository = $jugadorRepository;
    }

    public function findByDni(string $dni): ?JugadorDTO
    {
        $jugador = $this->jugadorRepository->findByDni($dni);

        if (!$jugador) {
            return null;
        }

        return JugadorDTO::fromModel($jugador);
    }


    public function delete(int $id): bool
    {
        $jugador = $this->jugadorRepository->findById($id);

        if (!$jugador) {
            return false;
        }

        return $this->jugadorRepository->delete($id);
    }


    public function restore(int $id): bool
    {
        $jugador = $this->jugadorRepository->findByIdWithTrashed($id);

        if (!$jugador) {
            throw new Exception("Jugador no encontrado o no está eliminado.");
        }

        return $this->jugadorRepository->restore($id);
    }


    public function calcularPuntaje(JugadorMasculino | JugadorFemenino $jugador): int
    {
        return match (true) {
            $jugador instanceof JugadorMasculino =>
            $jugador->habilidad + $jugador->fuerza + $jugador->velocidad + rand(0, 10),

            $jugador instanceof JugadorFemenino =>
            $jugador->habilidad + $jugador->reaccion + rand(0, 10),

            default => throw new InvalidArgumentException("Tipo de jugador no reconocido.")
        };
    }
}
