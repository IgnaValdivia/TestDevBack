<?php

namespace Tests\Unit;

use App\Models\Partida;
use App\Repositories\PartidaRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartidaRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private PartidaRepository $partidaRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->partidaRepository = new PartidaRepository();
    }

    public function test_puede_crear_partida()
    {
        $partida = Partida::factory()->create();

        $this->assertDatabaseHas('partidas', ['id' => $partida->id]);
    }

    public function test_puede_obtener_todas_las_partidas()
    {
        Partida::factory()->count(3)->create();

        $partidas = $this->partidaRepository->getAll();

        $this->assertCount(3, $partidas);
    }
}
