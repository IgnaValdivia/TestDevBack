<?php

namespace App\Models;

class JugadorFemenino extends Jugador
{
    protected $table = "jugadores_femeninos";

    protected $fillable = ['id', 'reaccion'];

    public function calcularPuntaje(): int
    {
        return $this->habilidad + $this->reaccion + rand(0, 10);
    }
}
