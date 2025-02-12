<?php

namespace App\Models;

class JugadorMasculino extends Jugador
{
    protected $table = "jugadores_masculinos";

    protected $fillable = ['id', 'fuerza', 'velocidad'];

    public function calcularPuntaje(): int
    {
        return $this->habilidad + $this->fuerza + $this->velocidad + rand(0, 10);
    }
}
