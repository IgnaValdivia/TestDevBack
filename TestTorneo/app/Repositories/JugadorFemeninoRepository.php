<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IJugadorFemeninoRepository;
use App\Models\JugadorFemenino;
use Illuminate\Support\Facades\DB;

class JugadorFemeninoRepository implements IJugadorFemeninoRepository
{
    public function getAll()
    {
        return JugadorFemenino::all();
    }

    public function findById(int $id): ?JugadorFemenino
    {
        return JugadorFemenino::find($id);
    }

    public function create(array $data): JugadorFemenino
    {
        $jugadorId = DB::table('jugadores')->insertGetId([
            'nombre' => $data['nombre'],
            'genero' => 'Femenino',
            'habilidad' => $data['habilidad'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return JugadorFemenino::create([
            'id' => $jugadorId,
            'reaccion' => $data['reaccion']
        ]);
    }

    public function update(int $id, array $data): bool
    {
        return JugadorFemenino::where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return JugadorFemenino::destroy($id);
    }
}
