<?php

namespace App\Services\Strategies;

use App\Models\Jugador;
use App\Services\Interfaces\IGanadorStrategy;

class GanadorPorHabilidad implements IGanadorStrategy
{
    public function determinarGanador(Jugador $jugador1, Jugador $jugador2): Jugador
    {
        $p1 = $jugador1->habilidad + rand(0, 10);
        $p2 = $jugador2->habilidad + rand(0, 10);

        if ($jugador1->genero === 'Masculino') {
            $p1 += $jugador1->fuerza + $jugador1->velocidad;
            $p2 += $jugador2->fuerza + $jugador2->velocidad;
        } else {
            $p1 += $jugador1->reaccion;
            $p2 += $jugador2->reaccion;
        }

        return $p1 >= $p2 ? $jugador1 : $jugador2;
    }
}
