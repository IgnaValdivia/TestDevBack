<?php

namespace App\Interfaces\Repositories;

use App\DTOs\JugadorDTO;
use App\Models\Jugador;

interface IJugadorRepository
{
    public function findById(int $id): ?Jugador;
    public function findByIdWithTrashed(int $id): ?Jugador;
    public function findByDni(string $dni): ?Jugador;
    public function delete(int $id): bool;
    public function restore(int $id): bool;
}
