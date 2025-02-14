<?php

namespace App\Interfaces\Repositories;

use App\DTOs\JugadorMasculinoDTO;

interface IJugadorMasculinoRepository
{
    public function getAll(): array;
    public function findById(int $id): ?JugadorMasculinoDTO;
    public function create(array $data): JugadorMasculinoDTO;
    public function update(int $id, array $data): bool;
}
