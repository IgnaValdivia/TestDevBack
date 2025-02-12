<?php

namespace Tests\Unit;

use App\Models\JugadorMasculino;
use App\Repositories\JugadorMasculinoRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JugadorMasculinoRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private JugadorMasculinoRepository $jugadorMasculinoRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->jugadorMasculinoRepository = new JugadorMasculinoRepository();
    }

    /*public function test_puede_crear_jugador_masculino()
    {
        $data = [
            'nombre' => 'Jugador Test Masculino',
            'genero' => 'Masculino',
            'habilidad' => 80,
            'fuerza' => 90,
            'velocidad' => 75
        ];

        $jugador = $this->jugadorMasculinoRepository->create($data);

        $this->assertDatabaseHas('jugadores', ['id' => $jugador->id, 'genero' => 'Masculino']);
        $this->assertDatabaseHas('jugadores_masculinos', ['id' => $jugador->id]);
    }

    public function test_puede_obtener_jugador_masculino_por_id()
    {
        $jugador = JugadorMasculino::factory()->create();

        $jugadorEncontrado = $this->jugadorMasculinoRepository->findById($jugador->id);

        $this->assertNotNull($jugadorEncontrado);
        $this->assertEquals($jugador->id, $jugadorEncontrado->id);
    }

    public function test_puede_listar_todos_los_jugadores_masculinos()
    {
        JugadorMasculino::factory()->count(3)->create();

        $jugadores = $this->jugadorMasculinoRepository->getAll();

        $this->assertCount(3, $jugadores);
    }

    public function test_puede_actualizar_jugador_masculino()
    {
        $jugador = JugadorMasculino::factory()->create();

        $actualizado = $this->jugadorMasculinoRepository->update($jugador->id, ['fuerza' => 99]);

        $this->assertTrue($actualizado);
        $this->assertDatabaseHas('jugadores_masculinos', ['id' => $jugador->id, 'fuerza' => 99]);
    }

    public function test_puede_eliminar_jugador_masculino()
    {
        $jugador = JugadorMasculino::factory()->create();

        $eliminado = $this->jugadorMasculinoRepository->delete($jugador->id);

        $this->assertTrue($eliminado);
        $this->assertDatabaseMissing('jugadores', ['id' => $jugador->id]);
        $this->assertDatabaseMissing('jugadores_masculinos', ['id' => $jugador->id]);
    }*/
}
