<?php

namespace App\Interfaces\Repositories;

use App\Models\Torneo;

interface ITorneoRepository
{
    public function getAll();
    public function findById(int $id): ?Torneo;
    public function create(array $data): Torneo;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function restore(int $id): bool;
    public function findByIdWithTrashed(int $id): ?Torneo;
}
