<?php

namespace App\Interfaces\Repositories;

use App\Models\Jugador;

interface IJugadorRepository
{
    public function getAll();
    public function findById(int $id): ?Jugador;
    public function create(array $data): Jugador;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
