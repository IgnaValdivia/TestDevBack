<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\JugadorMasculino;
use App\Models\JugadorFemenino;

class JugadorApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_puede_crear_jugador_masculino()
    {
        $data = [
            'nombre' => 'Carlos Pérez',
            'genero' => 'Masculino',
            'habilidad' => 85,
            'fuerza' => 90,
            'velocidad' => 75
        ];

        $response = $this->postJson('/api/jugadores', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('jugadores', ['nombre' => 'Carlos Pérez']);
    }

    public function test_puede_crear_jugador_femenino()
    {
        $data = [
            'nombre' => 'María Gómez',
            'genero' => 'Femenino',
            'habilidad' => 88,
            'reaccion' => 95
        ];

        $response = $this->postJson('/api/jugadores', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('jugadores', ['nombre' => 'María Gómez']);
    }

    public function test_puede_listar_jugadores()
    {
        JugadorMasculino::factory()->count(2)->create();
        JugadorFemenino::factory()->count(2)->create();

        $response = $this->getJson('/api/jugadores');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'masculinos' => [['id', 'nombre', 'genero']],
                'femeninos' => [['id', 'nombre', 'genero']]
            ]);
    }
}
