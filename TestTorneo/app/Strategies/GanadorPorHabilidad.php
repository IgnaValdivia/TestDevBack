<?php

namespace App\Strategies;

use App\Models\Jugador;
use App\Interfaces\IGanadorStrategy;

class GanadorPorHabilidad implements IGanadorStrategy
{
    public function determinarGanador(Jugador $jugador1, Jugador $jugador2): Jugador
    {
        return $jugador1->calcularPuntaje() >= $jugador2->calcularPuntaje() ? $jugador1 : $jugador2;
    }
}
