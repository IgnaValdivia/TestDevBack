<?php

namespace App\Interfaces\Repositories;

use App\DTOs\JugadorFemeninoDTO;

interface IJugadorFemeninoRepository
{
    public function getAll(): array;
    public function findById(int $id): ?JugadorFemeninoDTO;
    public function create(array $data): JugadorFemeninoDTO;
    public function update(int $id, array $data): bool;
}
