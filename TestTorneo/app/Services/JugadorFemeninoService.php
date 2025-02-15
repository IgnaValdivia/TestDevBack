<?php

namespace App\Services;

use App\DTOs\JugadorFemeninoDTO;
use App\Interfaces\IJugadorFemeninoService;
use App\Interfaces\Repositories\IJugadorFemeninoRepository;
use App\Interfaces\Repositories\IJugadorRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class JugadorFemeninoService implements IJugadorFemeninoService
{
    private IJugadorRepository $jugadorRepository;
    private IJugadorFemeninoRepository $jugadorFemeninoRepository;

    public function __construct(IJugadorRepository $jugadorRepository, IJugadorFemeninoRepository $jugadorFemeninoRepository)
    {
        $this->jugadorRepository = $jugadorRepository;
        $this->jugadorFemeninoRepository = $jugadorFemeninoRepository;
    }

    public function getAll(): array
    {
        return $this->jugadorFemeninoRepository->getAll()
            ->map(fn($jugadorFemenino) => JugadorFemeninoDTO::fromModel($jugadorFemenino))
            ->toArray();
    }

    public function findById(int $id): ?JugadorFemeninoDTO
    {
        $jugadorFemenino = $this->jugadorFemeninoRepository->findById($id);

        if (!$jugadorFemenino) {
            throw new Exception("Jugador no encontrado");
        }

        return JugadorFemeninoDTO::fromModel($jugadorFemenino);
    }

    public function create($data): JugadorFemeninoDTO
    {
        return DB::transaction(function () use ($data) {
            // Crear jugador base en `jugadores`
            $jugador = $this->jugadorRepository->create([
                'nombre' => $data['nombre'],
                'dni' => $data['dni'],
                'genero' => 'Femenino',
                'habilidad' => $data['habilidad'],
            ]);

            // Crear el jugador masculino usando el repositorio
            $jugadorFemenino = $this->jugadorFemeninoRepository->create($jugador, $data);

            // Retornar un DTO con los datos completos
            return JugadorFemeninoDTO::fromModel($jugadorFemenino);
        });
    }

    public function update(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $jugadorFemenino = $this->jugadorRepository->findById($id);

            if (!$jugadorFemenino) {
                throw new Exception("Jugador no encontrado");
            }

            // Actualizar los datos en `jugadores`
            $this->jugadorRepository->update($id, [
                'nombre' => $data['nombre'] ?? $jugadorFemenino->jugador->nombre,
                'dni' => $data['dni'] ?? $jugadorFemenino->jugador->dni,
                'habilidad' => $data['habilidad'] ?? $jugadorFemenino->jugador->habilidad,
            ]);

            // Actualizar los datos en `jugadores_masculinos`
            return $this->jugadorFemeninoRepository->update($id, [
                'reaccion' => $data['reaccion'] ?? $jugadorFemenino->reaccion,
            ]);
        });
    }
}
