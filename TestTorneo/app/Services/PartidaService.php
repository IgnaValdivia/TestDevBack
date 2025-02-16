<?php

namespace App\Services;

use App\Interfaces\IPartidaService;
use App\Interfaces\Repositories\IPartidaRepository;
use App\Models\Partida;
use Exception;

class PartidaService implements IPartidaService
{

    private IPartidaRepository $partidaRepository;

    public function __construct(IPartidaRepository $partidaRepository)
    {
        $this->partidaRepository = $partidaRepository;
    }

    public function findById(int $id): ?Partida
    {
        $partida = $this->partidaRepository->findById($id);

        if (!$partida) {
            throw new Exception("Partida no encontrada");
        }

        return $partida;
    }
}
