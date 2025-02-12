<?php

namespace App\Interfaces\Repositories;

use App\Models\JugadorMasculino;

interface IJugadorMasculinoRepository
{
    public function getAll();
    public function findById(int $id): ?JugadorMasculino;
    public function create(array $data): JugadorMasculino;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
