<?php

namespace App\Models;

use App\Services\Interfaces\ITorneoService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partida extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['jugador1_id', 'jugador2_id', 'ganador_id', 'ronda', 'torneo_id'];

    public function jugador1()
    {
        return $this->morphTo(null, 'jugador1_type', 'jugador1_id');
    }

    public function jugador2()
    {
        return $this->morphTo(null, 'jugador2_type', 'jugador2_id');
    }

    public function ganador()
    {
        return $this->morphTo();
    }

    public function torneo()
    {
        return $this->belongsTo(Torneo::class, 'torneo_id');
    }
}
