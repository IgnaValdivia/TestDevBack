<?php

namespace App\Interfaces;

use App\DTOs\JugadorDTO;
use App\Models\JugadorFemenino;
use App\Models\JugadorMasculino;

interface IJugadorService
{
    public function findByDni(string $dni): ?JugadorDTO;
    public function update(JugadorDTO $jugador, array $data): bool;
    public function delete(int $id): ?bool;
    public function restore(int $id): ?bool;
    public function calcularPuntaje(JugadorMasculino | JugadorFemenino $jugador): int;
    public function getTorneos(int $id,  ?bool $soloGanados): array;
    public function getPartidas(int $id, string $filtro): array;
}
