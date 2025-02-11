<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    use HasFactory;

    protected $fillable = ['jugador1_id', 'jugador2_id', 'ganador_id', 'ronda', 'torneo_id'];

    public function jugador1()
    {
        return $this->belongsTo(Jugador::class, 'jugador1_id');
    }

    public function jugador2()
    {
        return $this->belongsTo(Jugador::class, 'jugador2_id');
    }

    public function ganador()
    {
        return $this->belongsTo(Jugador::class, 'ganador_id');
    }

    public function torneo()
    {
        return $this->belongsTo(Torneo::class, 'torneo_id');
    }
}
