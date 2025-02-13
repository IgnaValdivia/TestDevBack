<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Interfaces\ITorneoService;
use App\Interfaces\Repositories\IPartidaRepository;
use Illuminate\Http\Request;

class PartidaController extends Controller
{
    private IPartidaRepository $partidaRepository;
    private ITorneoService $torneoService;

    public function __construct(IPartidaRepository $partidaRepo, ITorneoService $torneoService)
    {
        $this->partidaRepository = $partidaRepo;
        $this->torneoService = $torneoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->partidaRepository->getAll());
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $partida = $this->partidaRepository->findById($id);

        if (!$partida) {
            return response()->json(['error' => 'Partida no encontrada'], 404);
        }

        return response()->json($partida);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function determinarGanador($id)
    {
        $partida = $this->partidaRepository->findById($id);

        if (!$partida) {
            return response()->json(['error' => 'Partida no encontrada'], 404);
        }

        $ganador = $this->torneoService->determinarGanador($partida);
        $this->partidaRepository->update($id, ['ganador_id' => $ganador->id]);

        return response()->json(['ganador' => $ganador]);
    }
}
