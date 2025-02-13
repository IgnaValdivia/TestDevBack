<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JugadorFemenino extends Model
{

    use HasFactory;

    protected $table = "jugadores_femeninos";

    protected $fillable = ['id', 'reaccion'];

    public $incrementing = false;
    protected $keyType = 'int';
    protected $primaryKey = 'id';

    public function jugador()
    {
        return $this->belongsTo(Jugador::class, 'id');
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->jugador->nombre,
            'genero' => $this->jugador->genero,
            'habilidad' => $this->jugador->habilidad,
            'reaccion' => $this->reaccion,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
