<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IJugadorMasculinoRepository;
use App\Models\JugadorMasculino;
use Illuminate\Support\Facades\DB;

class JugadorMasculinoRepository implements IJugadorMasculinoRepository
{
    public function getAll()
    {
        return JugadorMasculino::all();
    }

    public function findById(int $id): ?JugadorMasculino
    {
        return JugadorMasculino::find($id);
    }

    public function create(array $data): JugadorMasculino
    {
        $jugadorId = DB::table('jugadores')->insertGetId([
            'nombre' => $data['nombre'],
            'genero' => 'Masculino',
            'habilidad' => $data['habilidad'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return JugadorMasculino::create([
            'id' => $jugadorId,
            'fuerza' => $data['fuerza'],
            'velocidad' => $data['velocidad']
        ]);
    }

    public function update(int $id, array $data): bool
    {
        return JugadorMasculino::where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return JugadorMasculino::destroy($id);
    }
}
