<?php

namespace Database\Seeders;

use App\Models\JugadorFemenino;
use App\Models\JugadorMasculino;
use App\Models\Partida;
use App\Models\Torneo;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*$this->call([
            JugadorSeeder::class,
        ]);*/

        /*$this->call([
            TorneoMasculinoSeeder::class,
            TorneoFemeninoSeeder::class,
        ]);*/

        JugadorFemenino::factory(10)->create();
        JugadorMasculino::factory(10)->create();
        Partida::factory(10)->create();
        Torneo::factory(10)->create();
    }
}
