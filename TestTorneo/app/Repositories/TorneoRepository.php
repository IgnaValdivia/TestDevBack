<?php

namespace App\Repositories;

use App\Interfaces\Repositories\ITorneoRepository;
use App\Models\Partida;
use App\Models\Torneo;
use Illuminate\Database\Eloquent\Collection;

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
        return Torneo::where('id', $id)->delete();
    }

    public function restore(int $id): bool
    {
        return Torneo::where('id', $id)->restore();
    }

    public function findByIdWithTrashed(int $id): ?Torneo
    {
        return Torneo::withTrashed()->find($id);
    }

    public function getPartidas(int $torneoId): Collection
    {
        return Partida::where('torneo_id', $torneoId)->get();
    }

    public function asignarJugadores(int $torneoId, array $jugadores): bool
    {
        $torneo = Torneo::find($torneoId);
        if (!$torneo) {
            return false;
        }

        $torneo->jugadores()->syncWithoutDetaching($jugadores);
        return true;
    }

    public function comenzarTorneo(int $torneoId): bool
    {
        $torneo = Torneo::find($torneoId);
        if (!$torneo || $torneo->estado !== 'pendiente') {
            return false;
        }

        return $torneo->update(['estado' => 'en curso']);
    }

    public function getEstado(int $torneoId): ?string
    {
        $torneo = Torneo::find($torneoId);
        return $torneo ? $torneo->estado : null;
    }

    public function getPartidasPorRonda(int $torneoId, int $ronda): Collection
    {
        return Partida::where('torneo_id', $torneoId)
            ->where('ronda', $ronda)
            ->get();
    }
}
