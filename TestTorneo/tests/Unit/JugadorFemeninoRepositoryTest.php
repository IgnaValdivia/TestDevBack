<?php

namespace Tests\Unit;

use App\Models\JugadorFemenino;
use App\Repositories\JugadorFemeninoRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class JugadorFemeninoRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private JugadorFemeninoRepository $jugadorFemeninoRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->jugadorFemeninoRepository = new JugadorFemeninoRepository();
    }

    /*public function test_puede_crear_jugador_femenino()
    {
        $data = [
            'nombre' => 'Jugador Test Femenino',
            'genero' => 'Femenino',
            'habilidad' => 85,
            'reaccion' => 92
        ];

        DB::beginTransaction();
        $jugador = $this->jugadorFemeninoRepository->create($data);
        DB::commit();

        $this->assertDatabaseHas('jugadores', ['id' => $jugador->id, 'genero' => 'Femenino']);
        $this->assertDatabaseHas('jugadores_femeninos', ['id' => $jugador->id]);
    }

    public function test_puede_obtener_jugador_femenino_por_id()
    {
        $jugador = JugadorFemenino::factory()->create();

        $jugadorEncontrado = $this->jugadorFemeninoRepository->findById($jugador->id);

        $this->assertNotNull($jugadorEncontrado);
        $this->assertEquals($jugador->id, $jugadorEncontrado->id);
    }

    public function test_puede_listar_todos_los_jugadores_femeninos()
    {
        JugadorFemenino::factory()->count(4)->create();

        $jugadores = $this->jugadorFemeninoRepository->getAll();

        $this->assertCount(4, $jugadores);
    }

    public function test_puede_actualizar_jugador_femenino()
    {
        $jugador = JugadorFemenino::factory()->create();

        $actualizado = $this->jugadorFemeninoRepository->update($jugador->id, ['reaccion' => 98]);

        $this->assertTrue($actualizado);
        $this->assertDatabaseHas('jugadores_femeninos', ['id' => $jugador->id, 'reaccion' => 98]);
    }

    public function test_puede_eliminar_jugador_femenino()
    {
        $jugador = JugadorFemenino::factory()->create();

        $eliminado = $this->jugadorFemeninoRepository->delete($jugador->id);

        $this->assertTrue($eliminado);
        $this->assertDatabaseMissing('jugadores', ['id' => $jugador->id]);
        $this->assertDatabaseMissing('jugadores_femeninos', ['id' => $jugador->id]);
    }*/
}
