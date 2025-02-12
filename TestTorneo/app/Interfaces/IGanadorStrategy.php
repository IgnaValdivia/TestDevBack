<?php

namespace App\Interfaces;

use App\Models\Jugador;

interface IGanadorStrategy
{
    public function determinarGanador(Jugador $jugador1, Jugador $jugador2): Jugador;
}
