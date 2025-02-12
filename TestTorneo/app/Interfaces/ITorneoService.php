<?php

namespace App\Services\Interfaces;

use App\Models\Partida;
use App\Models\Torneo;

interface ITorneoService
{
    public function comenzarTorneo(int $torneoId): ?Torneo;
    public function totalPartidasJugadas(Torneo $torneo): int;
    public function determinarGanador(Partida $partida);
}
