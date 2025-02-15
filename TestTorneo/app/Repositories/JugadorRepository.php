<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IJugadorRepository;
use App\Models\Jugador;

class JugadorRepository implements IJugadorRepository
{

    public function create(array $data): Jugador
    {
        return Jugador::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Jugador::where('id', $id)->update($data);
    }

    public function findById(int $id): ?Jugador
    {
        return Jugador::find($id);
    }

    public function findByDni(string $dni): ?Jugador
    {
        return Jugador::with(['jugadorMasculino', 'jugadorFemenino'])
            ->where('dni', $dni)
            ->first();
    }

    public function delete(int $id): bool
    {
        return Jugador::where('id', $id)->delete();
    }

    public function findByIdWithTrashed(int $id): ?Jugador
    {
        return Jugador::withTrashed()->find($id);
    }

    public function restore(int $id): bool
    {
        return Jugador::withTrashed()->where('id', $id)->restore();
    }
}
