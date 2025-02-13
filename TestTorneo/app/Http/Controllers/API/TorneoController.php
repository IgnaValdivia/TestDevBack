<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Interfaces\Repositories\ITorneoRepository;
use Illuminate\Http\Request;

class TorneoController extends Controller
{
    private ITorneoRepository $torneoRepository;

    public function __construct(ITorneoRepository $torneoRepository)
    {
        $this->torneoRepository = $torneoRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->torneoRepository->getAll());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'tipo' => 'required|string'
        ]);

        $torneo = $this->torneoRepository->create($request->all());
        return response()->json($torneo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $torneo = $this->torneoRepository->findById($id);

        if (!$torneo) {
            return response()->json(['error' => 'Torneo no encontrado'], 404);
        }

        return response()->json($torneo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $torneo = $this->torneoRepository->findById($id);

        if (!$torneo) {
            return response()->json(['error' => 'Torneo no encontrado'], 404);
        }

        $this->torneoRepository->update($id, $request->all());

        return response()->json(['message' => 'Torneo actualizado correctamente']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!$this->torneoRepository->delete($id)) {
            return response()->json(['error' => 'Torneo no encontrado'], 404);
        }

        return response()->json(['message' => 'Torneo eliminado correctamente']);
    }

    public function partidas($id)
    {
        $torneo = $this->torneoRepository->findById($id);

        if (!$torneo) {
            return response()->json(['error' => 'Torneo no encontrado'], 404);
        }

        return response()->json($torneo->partidas);
    }
}
