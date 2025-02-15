<?php

namespace App\Interfaces\Repositories;

use App\Models\Jugador;
use App\Models\JugadorFemenino;
use Illuminate\Database\Eloquent\Collection;

interface IJugadorFemeninoRepository
{
    public function getAll(): Collection;
    public function findById(int $id): ?JugadorFemenino;
    public function create(Jugador $jugador, array $data): JugadorFemenino;
    public function update(int $id, array $data): bool;
}
