<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IJugadorFemeninoRepository;
use App\Models\Jugador;
use App\Models\JugadorFemenino;
use Illuminate\Database\Eloquent\Collection;

class JugadorFemeninoRepository implements IJugadorFemeninoRepository
{
    public function getAll(): Collection
    {
        return JugadorFemenino::whereHas('jugador')
            ->with('jugador')
            ->get();
    }

    public function findById(int $id): ?JugadorFemenino
    {
        return JugadorFemenino::find($id);
    }


    public function create(Jugador $jugador, array $data): JugadorFemenino
    {
        return JugadorFemenino::create([
            'id' => $jugador->id,
            'reaccion' => $data['reaccion'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        return JugadorFemenino::where('id', $id)->update($data);
    }
}
