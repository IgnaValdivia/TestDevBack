<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Partida;
use App\Models\Torneo;
use App\Models\Jugador;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class PartidaApiTest extends TestCase
{
    use RefreshDatabase;


    /*public function obtiene_una_partida_por_id()
    {
        // 📌 Crear datos de prueba
        $torneo = Torneo::factory()->create();
        $jugador1 = Jugador::factory()->create();
        $jugador2 = Jugador::factory()->create();
        $partida = Partida::factory()->create([
            'torneo_id' => $torneo->id,
            'jugador1_id' => $jugador1->id,
            'jugador2_id' => $jugador2->id,
        ]);

        // 📌 Hacer la petición a la API
        $response = $this->getJson(route('partidas.show', ['id' => $partida->id]));

        // 📌 Verificar la respuesta
        $response->assertStatus(200)
            ->assertJson([
                'id' => $partida->id,
                'torneo_id' => $torneo->id,
                'jugador1_id' => $jugador1->id,
                'jugador2_id' => $jugador2->id,
            ]);
    }*/

    #[Test]
    public function devuelve_404_si_la_partida_no_existe()
    {
        //Hacer la petición con un ID inexistente
        $response = $this->getJson(route('partidas.show', ['id' => 999]));

        //Verificar la respuesta
        $response->assertStatus(404)
            ->assertJson(['error' => 'Partida no encontrada']);
    }
}
