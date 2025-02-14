<?php

namespace App\Repositories;

use App\DTOs\JugadorFemeninoDTO;
use App\Interfaces\Repositories\IJugadorFemeninoRepository;
use App\Models\Jugador;
use App\Models\JugadorFemenino;
use Illuminate\Support\Facades\DB;

class JugadorFemeninoRepository implements IJugadorFemeninoRepository
{
    public function getAll(): array
    {
        return JugadorFemenino::whereHas('jugador') //Solo incluye si el jugador existe y no está eliminado
            ->with('jugador') // Cargar la relación
            ->get()
            ->map(fn($jugadorFemenino) => JugadorFemeninoDTO::fromModel($jugadorFemenino))
            ->toArray();
    }

    public function findById(int $id): ?JugadorFemeninoDTO
    {
        $jugadorFemenino = JugadorFemenino::with('jugador')->find($id);

        if (!$jugadorFemenino) {
            return null;
        }

        return JugadorFemeninoDTO::fromModel($jugadorFemenino);
    }


    public function create($data): JugadorFemeninoDTO
    {
        $jugador = Jugador::create([
            'nombre' => $data['nombre'],
            'dni' => $data['dni'],
            'genero' => 'Masculino',
            'habilidad' => $data['habilidad'],
        ]);

        JugadorFemenino::create([
            'id' => $jugador->id,
            'reaccion' => $data['reaccion'],
        ]);


        return $this->findById($jugador->id);
    }

    public function update(int $id, array $data): bool
    {
        // Buscar el jugador masculino con su relación
        $jugadorFemenino = JugadorFemenino::with('jugador')->find($id);

        if (!$jugadorFemenino) {
            return false; // Si no existe, retornamos false
        }

        // Actualizar datos en Jugador
        $jugadorFemenino->jugador->update([
            'nombre' => $data['nombre'] ?? $jugadorFemenino->jugador->nombre,
            'dni' => $data['dni'] ?? $jugadorFemenino->jugador->dni,
            'habilidad' => $data['habilidad'] ?? $jugadorFemenino->jugador->habilidad,
        ]);

        // Actualizar datos en JugadorFemenino
        return $jugadorFemenino->update([
            'reaccion' => $data['reaccion'] ?? $jugadorFemenino->reaccion,
        ]);
    }
}
