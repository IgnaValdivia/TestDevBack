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
     *     description="Obtiene una lista de todos los torneos disponibles. Si no hay torneos, devuelve un mensaje indicando que no hay torneos disponibles.",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de torneos o mensaje de que no hay torneos disponibles",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="nombre", type="string", example="Torneo de Verano"),
     *                         @OA\Property(property="fecha", type="string", format="date", example="2025-03-15"),
     *                         @OA\Property(property="tipo", type="string", example="Eliminación directa"),
     *                         @OA\Property(property="ganador_id", type="integer", nullable=true, example=10)
     *                     )
     *                 ),
     *                 @OA\Schema(
     *                     type="object",
     *                     @OA\Property(property="message", type="string", example="No hay torneos disponibles")
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error en el servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error inesperado en el servidor")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $torneos = $this->torneoService->getAll();

        if (empty($torneos)) {
            return response()->json(['message' => 'No hay torneos disponibles'], 200);
        }

        return response()->json($torneos, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/torneos",
     *     tags={"Torneos"},
     *     summary="Crear un nuevo torneo",
     *     description="Registra un nuevo torneo con nombre, tipo y fecha.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "tipo", "fecha"},
     *             @OA\Property(property="nombre", type="string", example="Torneo Nacional"),
     *             @OA\Property(property="tipo", type="string", enum={"Masculino", "Femenino"}, example="Masculino"),
     *             @OA\Property(property="fecha", type="string", format="date", example="2025-03-01")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Torneo creado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nombre", type="string", example="Torneo Nacional"),
     *             @OA\Property(property="tipo", type="string", example="Masculino"),
     *             @OA\Property(property="fecha", type="string", format="date", example="2025-03-01"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-01T12:34:56Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-01T12:34:56Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la validación de datos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Los datos proporcionados no son válidos"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="nombre", type="array", @OA\Items(type="string", example="El nombre es obligatorio")),
     *                 @OA\Property(property="tipo", type="array", @OA\Items(type="string", example="El tipo debe ser Masculino o Femenino")),
     *                 @OA\Property(property="fecha", type="array", @OA\Items(type="string", example="La fecha no es válida"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Error inesperado en el servidor")
     *         )
     *     )
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
            'estado' => 'sometimes|in:Finalizado,Pendiente',
            'ganador_id' => 'sometimes|integer',
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
     *     description="Devuelve la lista de partidas de un torneo específico.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del torneo",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de partidas del torneo",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=101),
     *                 @OA\Property(property="torneo_id", type="integer", example=1),
     *                 @OA\Property(property="jugador1_id", type="integer", example=10),
     *                 @OA\Property(property="jugador2_id", type="integer", example=20),
     *                 @OA\Property(property="ganador_id", type="integer", example=10, nullable=true),
     *                 @OA\Property(property="ronda", type="integer", example=1),
     *                 @OA\Property(property="fecha", type="string", format="date-time", example="2025-03-01T12:34:56Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Torneo no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Torneo con id 1 no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="No hay partidas disponibles para el torneo",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No hay partidas disponibles para el torneo con id: 1")
     *         )
     *     )
     * )
     */
    public function partidas(int $id): JsonResponse
    {
        $partidas = $this->torneoService->getPartidas($id);

        if (empty($partidas)) {
            return response()->json(['message' => 'No hay partidas disponibles para el torneo con id: ' . $id], 200);
        }

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

        return $estado
            ? response()->json(['estado' => $estado], 200)
            : response()->json(['message' => 'Torneo no encontrado'], 404);
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
