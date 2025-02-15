<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IJugadorMasculinoRepository;
use App\Models\Jugador;
use App\Models\JugadorMasculino;
use Illuminate\Database\Eloquent\Collection;

class JugadorMasculinoRepository implements IJugadorMasculinoRepository
{
    public function getAll(): Collection
    {
        return JugadorMasculino::whereHas('jugador') //Solo incluye si el jugador existe y no está eliminado
            ->with('jugador') // Cargar la relación
            ->get();
    }

    public function findById(int $id): ?JugadorMasculino
    {
        return JugadorMasculino::find($id);
    }


    public function create(Jugador $jugador, array $data): JugadorMasculino
    {
        return JugadorMasculino::create([
            'id' => $jugador->id,
            'fuerza' => $data['fuerza'],
            'velocidad' => $data['velocidad']
        ]);
    }

    public function update(int $id, array $data): bool
    {
        return JugadorMasculino::where('id', $id)->update($data);
    }
}
