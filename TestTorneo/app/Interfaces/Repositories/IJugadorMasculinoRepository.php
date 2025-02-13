<?php

namespace App\Interfaces\Repositories;

use App\DTOs\JugadorMasculinoDTO;
use App\Models\Jugador;
use App\Models\JugadorMasculino;

interface IJugadorMasculinoRepository
{
    public function getAll(): array;
    public function findById(int $id): ?JugadorMasculinoDTO;
    public function create(array $data): JugadorMasculinoDTO;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
