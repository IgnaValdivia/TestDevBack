<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jugador extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'genero', 'habilidad', 'fuerza', 'velocidad', 'reaccion'];

    public function torneos()
    {
        return $this->belongsToMany(Torneo::class, 'torneo_jugador');
    }
}
