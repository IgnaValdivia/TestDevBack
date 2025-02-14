<?php

namespace App\Interfaces;

use App\Models\Partida;
use App\Models\Torneo;

interface ITorneoService
{
    public function getAll();
    public function create(array $data): Torneo;
    public function delete(int $id): bool;
    public function restore(int $id): bool;
    public function determinarGanador(Partida $partida);
    public function actualizarGanador(int $torneoId, int $ganadorId): bool;
}
