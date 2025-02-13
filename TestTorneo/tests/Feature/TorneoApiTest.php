<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Torneo;

class TorneoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_puede_listar_torneos()
    {
        Torneo::factory()->count(3)->create();

        $response = $this->getJson('/api/torneos');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_puede_crear_torneo()
    {
        $data = [
            'nombre' => 'Torneo de Prueba',
            'tipo' => 'Masculino'
        ];

        $response = $this->postJson('/api/torneos', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('torneos', $data);
    }

    public function test_puede_obtener_un_torneo_por_id()
    {
        $torneo = Torneo::factory()->create();

        $response = $this->getJson("/api/torneos/{$torneo->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['nombre' => $torneo->nombre]);
    }

    public function test_puede_actualizar_un_torneo()
    {
        $torneo = Torneo::factory()->create();
        $nuevosDatos = ['nombre' => 'Torneo Actualizado'];

        $response = $this->putJson("/api/torneos/{$torneo->id}", $nuevosDatos);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Torneo actualizado correctamente']);

        $this->assertDatabaseHas('torneos', $nuevosDatos);
    }

    public function test_puede_eliminar_un_torneo()
    {
        $torneo = Torneo::factory()->create();

        $response = $this->deleteJson("/api/torneos/{$torneo->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Torneo eliminado correctamente']);

        $this->assertDatabaseMissing('torneos', ['id' => $torneo->id]);
    }
}
