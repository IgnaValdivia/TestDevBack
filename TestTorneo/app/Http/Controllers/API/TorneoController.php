<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Interfaces\ITorneoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Torneos",
 *     description="Endpoints relacionados con los torneos"
 * )
 */
class TorneoController extends Controller
{
    private ITorneoService $torneoService;

    public function __construct(ITorneoService $torneoService)
    {
        $this->torneoService = $torneoService;
    }


    /**
     * @OA\Get(
     *     path="/api/torneos",
     *     tags={"Torneos"},
     *     summary="Listar todos los torneos",
     *     @OA\Response(response=200, description="Lista de torneos"),
     *     @OA\Response(response=500, description="Error en el servidor")
     * )
     */
    public function index(): JsonResponse
    {
        $torneos = $this->torneoService->getAll();
        return response()->json($torneos, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/torneos",
     *     tags={"Torneos"},
     *     summary="Crear un nuevo torneo",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "tipo", "fecha"},
     *             @OA\Property(property="nombre", type="string", example="Torneo Nacional"),
     *             @OA\Property(property="tipo", type="string", enum={"Masculino", "Femenino"}, example="Masculino"),
     *             @OA\Property(property="fecha", type="string", format="date", example="2025-03-01")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Torneo creado"),
     *     @OA\Response(response=400, description="Datos inválidos")
     * )
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
     * @OA\Get(
     *     path="/api/torneos/{id}",
     *     tags={"Torneos"},
     *     summary="Obtener un torneo por ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Torneo encontrado"),
     *     @OA\Response(response=404, description="Torneo no encontrado")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $torneo = $this->torneoService->findById($id);
        return $torneo
            ? response()->json($torneo, 200)
            : response()->json(['message' => 'Torneo no encontrado'], 404);
    }

    /**
     * @OA\Put(
     *     path="/api/torneos/{id}",
     *     tags={"Torneos"},
     *     summary="Actualizar un torneo",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string"),
     *             @OA\Property(property="tipo", type="string", enum={"Masculino", "Femenino"}),
     *             @OA\Property(property="fecha", type="string", format="date")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Torneo actualizado"),
     *     @OA\Response(response=404, description="Torneo no encontrado")
     * )
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
     * @OA\Delete(
     *     path="/api/torneos/{id}",
     *     tags={"Torneos"},
     *     summary="Eliminar un torneo",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Torneo eliminado"),
     *     @OA\Response(response=404, description="Torneo no encontrado")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $eliminado = $this->torneoService->delete($id);
        return $eliminado
            ? response()->json(['message' => 'Torneo eliminado'], 200)
            : response()->json(['message' => 'Torneo no encontrado'], 404);
    }

    /**
     * @OA\Get(
     *     path="/api/torneos/{id}/partidas",
     *     tags={"Torneos"},
     *     summary="Obtener todas las partidas de un torneo",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Lista de partidas del torneo")
     * )
     */
    public function partidas(int $id): JsonResponse
    {
        $partidas = $this->torneoService->getPartidas($id);
        return response()->json($partidas, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/torneos/{id}/asignar-jugadores",
     *     tags={"Torneos"},
     *     summary="Asignar jugadores a un torneo",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="jugadores", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(response=200, description="Jugadores asignados correctamente"),
     *     @OA\Response(response=400, description="Error al asignar jugadores")
     * )
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
     * @OA\Post(
     *     path="/api/torneos/{id}/comenzar",
     *     tags={"Torneos"},
     *     summary="Comenzar un torneo",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Torneo comenzado"),
     *     @OA\Response(response=400, description="No se pudo comenzar el torneo")
     * )
     */
    public function comenzarTorneo(int $id): JsonResponse
    {
        $comenzado = $this->torneoService->comenzarTorneo($id);
        return $comenzado
            ? response()->json(['message' => 'Torneo comenzado'], 200)
            : response()->json(['message' => 'No se pudo comenzar el torneo'], 400);
    }

    /**
     * @OA\Get(
     *     path="/api/torneos/{id}/estado",
     *     tags={"Torneos"},
     *     summary="Obtener el estado actual de un torneo",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Estado del torneo"),
     *     @OA\Response(response=404, description="Torneo no encontrado")
     * )
     */
    public function estadoTorneo(int $id): JsonResponse
    {
        $estado = $this->torneoService->getEstado($id);
        return response()->json(['estado' => $estado], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/torneos/{id}/partidas/{ronda}",
     *     tags={"Torneos"},
     *     summary="Obtener partidas de una ronda específica de un torneo",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="ronda", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Lista de partidas de la ronda"),
     *     @OA\Response(response=404, description="No hay partidas en esta ronda")
     * )
     */
    public function partidasPorRonda(int $id, int $ronda): JsonResponse
    {
        $partidas = $this->torneoService->getPartidasPorRonda($id, $ronda);
        return response()->json($partidas, 200);
    }
}
