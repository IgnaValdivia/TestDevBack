<?php

namespace App\DTOs;

use App\Models\Torneo;

class TorneoPartidasDTO
{
    public static function fromModel(Torneo $torneo): array
    {
        return [
            'id' => $torneo->id,
            'nombre' => $torneo->nombre,
            'tipo' => $torneo->tipo,
            'estado' => $torneo->estado,
            'ganador_id' => $torneo->ganador_id,
            'fecha' => $torneo->fecha,
            'partidas' => $torneo->partidas->map(fn($partida) => [
                'id' => $partida->id,
                'ronda' => $partida->ronda,
                'jugador1_id' => $partida->jugador1_id,
                'jugador2_id' => $partida->jugador2_id,
                'ganador_id' => $partida->ganador_id,
            ])->toArray(),
        ];
    }
}
