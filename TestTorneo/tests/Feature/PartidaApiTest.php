<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Partida;
use App\Models\JugadorMasculino;
use App\Models\Torneo;

class PartidaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_puede_crear_una_partida()
    {
        $torneo = Torneo::factory()->create();
        $jugador1 = JugadorMasculino::factory()->create();
        $jugador2 = JugadorMasculino::factory()->create();

        $data = [
            'torneo_id' => $torneo->id,
            'jugador1_id' => $jugador1->id,
            'jugador2_id' => $jugador2->id,
            'ronda' => 1
        ];

        $response = $this->postJson('/api/partidas', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['torneo_id' => $torneo->id]);

        $this->assertDatabaseHas('partidas', $data);
    }

    public function test_puede_determinar_un_ganador()
    {
        $torneo = Torneo::factory()->create();
        $jugador1 = JugadorMasculino::factory()->create();
        $jugador2 = JugadorMasculino::factory()->create();

        $partida = Partida::factory()->create([
            'torneo_id' => $torneo->id,
            'jugador1_id' => $jugador1->id,
            'jugador2_id' => $jugador2->id
        ]);

        $response = $this->postJson("/api/partidas/{$partida->id}/determinar-ganador");

        $response->assertStatus(200)
            ->assertJsonStructure(['ganador' => ['id', 'nombre']]);
    }

    public function test_puede_obtener_todas_las_partidas()
    {
        Partida::factory()->count(3)->create();

        $response = $this->getJson('/api/partidas');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }
}
