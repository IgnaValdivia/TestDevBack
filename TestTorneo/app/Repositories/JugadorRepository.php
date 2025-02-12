<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IJugadorRepository;
use App\Models\Jugador;

class JugadorRepository implements IJugadorRepository
{
    public function getAll()
    {
        return Jugador::all();
    }

    public function findById(int $id): ?Jugador
    {
        return Jugador::find($id);
    }

    public function create(array $data): Jugador
    {
        return Jugador::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Jugador::where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return Jugador::destroy($id);
    }
}
