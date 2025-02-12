<?php

namespace Tests\Unit;

use App\Models\Torneo;
use App\Repositories\TorneoRepository;
use App\Repositories\PartidaRepository;
use App\Strategies\GanadorPorHabilidad;
use App\Services\TorneoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TorneoServiceTest extends TestCase
{
    use RefreshDatabase;

    private TorneoService $torneoService;

    protected function setUp(): void
    {
        parent::setUp();
        $torneoRepository = new TorneoRepository();
        $partidaRepository = new PartidaRepository();
        $ganadorStrategy = new GanadorPorHabilidad();
        $this->torneoService = new TorneoService($torneoRepository, $partidaRepository, $ganadorStrategy);
    }

    public function test_puede_crear_torneo()
    {
        $data = [
            'nombre' => 'Torneo Test',
            'tipo' => 'Femenino',
            'fecha' => now()
        ];

        $torneo = $this->torneoService->crearTorneo($data);

        $this->assertDatabaseHas('torneos', ['nombre' => 'Torneo Test']);
        $this->assertEquals('Femenino', $torneo->tipo);
    }
}
