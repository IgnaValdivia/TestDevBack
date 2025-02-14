<?php

namespace App\Repositories;

use App\DTOs\JugadorMasculinoDTO;
use App\Interfaces\Repositories\IJugadorMasculinoRepository;
use App\Models\Jugador;
use App\Models\JugadorMasculino;

class JugadorMasculinoRepository implements IJugadorMasculinoRepository
{
    public function getAll(): array
    {
        return JugadorMasculino::whereHas('jugador') //Solo incluye si el jugador existe y no está eliminado
            ->with('jugador') // Cargar la relación
            ->get()
            ->map(fn($jugadorMasculino) => JugadorMasculinoDTO::fromModel($jugadorMasculino))
            ->toArray();
    }

    public function findById(int $id): ?JugadorMasculinoDTO
    {
        $jugadorMasculino = JugadorMasculino::with('jugador')->find($id);

        if (!$jugadorMasculino) {
            return null;
        }

        return JugadorMasculinoDTO::fromModel($jugadorMasculino);
    }


    public function create($data): JugadorMasculinoDTO
    {
        $jugador = Jugador::create([
            'nombre' => $data['nombre'],
            'dni' => $data['dni'],
            'genero' => 'Masculino',
            'habilidad' => $data['habilidad'],
        ]);

        JugadorMasculino::create([
            'id' => $jugador->id,
            'fuerza' => $data['fuerza'],
            'velocidad' => $data['velocidad']
        ]);


        return $this->findById($jugador->id);
    }

    public function update(int $id, array $data): bool
    {
        // Buscar el jugador masculino con su relación
        $jugadorMasculino = JugadorMasculino::with('jugador')->find($id);

        if (!$jugadorMasculino) {
            return false; // Si no existe, retornamos false
        }

        // Actualizar datos en Jugador
        $jugadorMasculino->jugador->update([
            'nombre' => $data['nombre'] ?? $jugadorMasculino->jugador->nombre,
            'dni' => $data['dni'] ?? $jugadorMasculino->jugador->dni,
            'habilidad' => $data['habilidad'] ?? $jugadorMasculino->jugador->habilidad,
        ]);

        // Actualizar datos en JugadorMasculino
        return $jugadorMasculino->update([
            'fuerza' => $data['fuerza'] ?? $jugadorMasculino->fuerza,
            'velocidad' => $data['velocidad'] ?? $jugadorMasculino->velocidad,
        ]);
    }
}
