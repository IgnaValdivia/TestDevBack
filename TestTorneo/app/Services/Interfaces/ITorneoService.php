<?php

namespace App\Services\Interfaces;

use App\Models\Torneo;

interface ITorneoService
{
    public function comenzarTorneo(int $torneoId): ?Torneo;
}
