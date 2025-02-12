<?php

namespace App\Repositories;

use App\Interfaces\Repositories\ITorneoRepository;
use App\Models\Torneo;

class TorneoRepository implements ITorneoRepository
{
    public function getAll()
    {
        return Torneo::all();
    }

    public function findById(int $id): ?Torneo
    {
        return Torneo::find($id);
    }

    public function create(array $data): Torneo
    {
        return Torneo::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Torneo::where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return Torneo::destroy($id);
    }
}
