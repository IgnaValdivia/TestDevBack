<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JugadorMasculino;
use App\Models\JugadorFemenino;
use Illuminate\Support\Facades\DB;

class JugadorSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            // Crear jugadores masculinos
            for ($i = 1; $i <= 5; $i++) {
                // Crear el jugador base en `jugadores`
                $jugador = DB::table('jugadores')->insertGetId([
                    'nombre' => "JugadorM$i",
                    'genero' => 'Masculino',
                    'habilidad' => rand(0, 100),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Crear el jugador en `jugadores_masculinos`
                JugadorMasculino::create([
                    'id' => $jugador,
                    'fuerza' => rand(0, 100),
                    'velocidad' => rand(0, 100)
                ]);
            }

            // Crear jugadores femeninos
            for ($i = 1; $i <= 5; $i++) {
                // Crear el jugador base en `jugadores`
                $jugador = DB::table('jugadores')->insertGetId([
                    'nombre' => "JugadorF$i",
                    'genero' => 'Femenino',
                    'habilidad' => rand(0, 100),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Crear el jugador en `jugadores_femeninos`
                JugadorFemenino::create([
                    'id' => $jugador,
                    'reaccion' => rand(0, 100)
                ]);
            }
        });
    }
}
