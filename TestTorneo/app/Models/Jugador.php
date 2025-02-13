<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jugador extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "jugadores";

    protected $fillable = ['nombre', 'genero', 'habilidad'];

    public function detalle()
    {
        return $this->hasOne(JugadorMasculino::class, 'id')->orWhereHas('jugador', function ($query) {
            $query->where('genero', 'Femenino');
        });
    }
}
