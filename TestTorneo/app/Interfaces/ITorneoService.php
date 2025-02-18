<?php

namespace App\Interfaces;

use App\DTOs\TorneoDTO;
use App\Models\Partida;
use App\Models\Torneo;
use Illuminate\Database\Eloquent\Collection;

interface ITorneoService
{
    public function getAll(): array;
    public function findById(int $id): ?TorneoDTO;
    public function create(array $data): TorneoDTO;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function restore(int $id): bool;
    public function determinarGanador(Partida $partida);
    public function actualizarGanador(int $torneoId, int $ganadorId): bool;
    public function getPartidas(int $id): array;
    public function asignarJugadores(int $id, array $jugadores): bool;
    public function comenzarTorneo(int $id): bool;
    public function getEstado(int $id): ?string;
    public function getPartidasPorRonda(int $id, int $ronda): Collection;
}
