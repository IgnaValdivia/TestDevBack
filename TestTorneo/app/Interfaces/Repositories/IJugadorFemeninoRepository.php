<?php

namespace App\Interfaces\Repositories;

use App\Models\JugadorFemenino;

interface IJugadorFemeninoRepository
{
    public function getAll();
    public function findById(int $id): ?JugadorFemenino;
    public function create(array $data): JugadorFemenino;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
