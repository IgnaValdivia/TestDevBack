<?php

namespace Tests\Unit;

use App\Models\Torneo;
use App\Repositories\TorneoRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TorneoRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TorneoRepository $torneoRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->torneoRepository = new TorneoRepository();
    }

    public function test_puede_crear_torneo()
    {
        $data = [
            'nombre' => 'Torneo de Test',
            'tipo' => 'Masculino',
            'fecha' => now()
        ];

        $torneo = $this->torneoRepository->create($data);

        $this->assertDatabaseHas('torneos', ['nombre' => 'Torneo de Test']);
        $this->assertEquals('Masculino', $torneo->tipo);
    }

    public function test_puede_obtener_todos_los_torneos()
    {
        Torneo::factory()->count(3)->create();

        $torneos = $this->torneoRepository->getAll();

        $this->assertCount(3, $torneos);
    }

    public function test_puede_eliminar_torneo()
    {
        $torneo = Torneo::factory()->create();

        $result = $this->torneoRepository->delete($torneo->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('torneos', ['id' => $torneo->id]);
    }
}
