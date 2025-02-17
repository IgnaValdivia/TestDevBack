<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Torneo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nombre', 'tipo', 'fecha', 'estado', 'ganador_id'];

    public function partidas()
    {
        return $this->hasMany(Partida::class);
    }

    public function jugadores()
    {
        return $this->belongsToMany(Jugador::class, 'torneo_jugador');
    }

    public function ganador()
    {
        return $this->belongsTo(Jugador::class, 'ganador_id');
    }
}
