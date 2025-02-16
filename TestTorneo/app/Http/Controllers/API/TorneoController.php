<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Interfaces\ITorneoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TorneoController extends Controller
{
    private ITorneoService $torneoService;

    public function __construct(ITorneoService $torneoService)
    {
        $this->torneoService = $torneoService;
    }


    /**
     * Listar todos los torneos.
     */
    public function index(): JsonResponse
    {
        $torneos = $this->torneoService->getAll();
        return response()->json($torneos, 200);
    }

    /**
     * Crear un nuevo torneo.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:Masculino,Femenino',
            'fecha' => 'required|date',
        ]);

        $torneo = $this->torneoService->create($data);
        return response()->json($torneo, 201);
    }

    /**
     * Obtener un torneo por ID.
     */
    public function show(int $id): JsonResponse
    {
        $torneo = $this->torneoService->findById($id);
        return $torneo
            ? response()->json($torneo, 200)
            : response()->json(['message' => 'Torneo no encontrado'], 404);
    }

    /**
     * Actualizar un torneo.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'tipo' => 'sometimes|in:Masculino,Femenino',
            'fecha' => 'sometimes|date',
        ]);

        $actualizado = $this->torneoService->update($id, $data);
        return $actualizado
            ? response()->json(['message' => 'Torneo actualizado'], 200)
            : response()->json(['message' => 'Torneo no encontrado'], 404);
    }

    /**
     * Eliminar un torneo.
     */
    public function destroy(int $id): JsonResponse
    {
        $eliminado = $this->torneoService->delete($id);
        return $eliminado
            ? response()->json(['message' => 'Torneo eliminado'], 200)
            : response()->json(['message' => 'Torneo no encontrado'], 404);
    }

    /**
     * Obtener todas las partidas de un torneo.
     */
    public function partidas(int $id): JsonResponse
    {
        $partidas = $this->torneoService->getPartidas($id);
        return response()->json($partidas, 200);
    }

    /**
     * Asignar jugadores a un torneo.
     */
    public function asignarJugadores(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'jugadores' => 'required|array',
            'jugadores.*' => 'integer|exists:jugadores,id',
        ]);

        $asignados = $this->torneoService->asignarJugadores($id, $data['jugadores']);

        return $asignados
            ? response()->json(['message' => 'Jugadores asignados correctamente'], 200)
            : response()->json(['message' => 'Error al asignar jugadores'], 400);
    }

    /**
     * Comenzar un torneo.
     */
    public function comenzarTorneo(int $id): JsonResponse
    {
        $comenzado = $this->torneoService->comenzarTorneo($id);
        return $comenzado
            ? response()->json(['message' => 'Torneo comenzado'], 200)
            : response()->json(['message' => 'No se pudo comenzar el torneo'], 400);
    }

    /**
     * Obtener el estado actual de un torneo.
     */
    public function estadoTorneo(int $id): JsonResponse
    {
        $estado = $this->torneoService->getEstado($id);
        return response()->json(['estado' => $estado], 200);
    }

    /**
     * Obtener partidas de una ronda específica de un torneo.
     */
    public function partidasPorRonda(int $id, int $ronda): JsonResponse
    {
        $partidas = $this->torneoService->getPartidasPorRonda($id, $ronda);
        return response()->json($partidas, 200);
    }
}
