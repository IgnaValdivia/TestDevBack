<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\JugadorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Jugadores",
 *     description="Endpoints relacionados con los jugadores"
 * )
 */
class JugadorController extends Controller
{

    private JugadorService $jugadorService;

    public function __construct(JugadorService $jugadorService)
    {
        $this->jugadorService = $jugadorService;
    }

    /**
     * Obtener todos los jugadores con filtro opcional por género.
     * 
     * @OA\Get(
     *     path="/api/jugadores",
     *     tags={"Jugadores"},
     *     summary="Listar todos los jugadores",
     *     description="Permite listar todos los jugadores, opcionalmente filtrando por género.",
     *     @OA\Parameter(
     *         name="genero",
     *         in="query",
     *         description="Filtrar por género (Masculino, Femenino, Todos)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Lista de jugadores"),
     *     @OA\Response(response=500, description="Error en el servidor")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $jugadores = $this->jugadorService->getAll($request->query('genero'));

        return response()->json($jugadores, 200);
    }

    /**
     * Crear un nuevo jugador.
     * 
     * @OA\Post(
     *     path="/api/jugadores",
     *     tags={"Jugadores"},
     *     summary="Crear un nuevo jugador",
     *     description="Registra un nuevo jugador con sus atributos.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "dni", "genero", "habilidad"},
     *             @OA\Property(property="nombre", type="string", example="Juan Pérez"),
     *             @OA\Property(property="dni", type="string", example="12345678"),
     *             @OA\Property(property="genero", type="string", enum={"Masculino", "Femenino"}),
     *             @OA\Property(property="habilidad", type="integer", example=85),
     *             @OA\Property(property="fuerza", type="integer", example=80, nullable=true),
     *             @OA\Property(property="velocidad", type="integer", example=90, nullable=true),
     *             @OA\Property(property="reaccion", type="integer", example=70, nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Jugador creado exitosamente"),
     *     @OA\Response(response=400, description="Error en la validación"),
     *     @OA\Response(response=500, description="Error en el servidor")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'dni' => 'required|string|unique:jugadores,dni',
            'genero' => 'required|in:Masculino,Femenino',
            'habilidad' => 'required|integer|min:0|max:100',
            'fuerza' => $request->genero === 'Masculino' ? 'required|integer|min:0|max:100' : 'nullable',
            'velocidad' => $request->genero === 'Masculino' ? 'required|integer|min:0|max:100' : 'nullable',
            'reaccion' => $request->genero === 'Femenino' ? 'required|integer|min:0|max:100' : 'nullable',
        ]);


        $jugador = $this->jugadorService->create($request->genero, $data);

        return response()->json($jugador, 201);
    }


    /**
     * Obtener un jugador por ID.
     * 
     * @OA\Get(
     *     path="/api/jugadores/{id}",
     *     tags={"Jugadores"},
     *     summary="Obtener un jugador por ID",
     *     description="Busca un jugador en base a su ID único.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del jugador",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Jugador encontrado"),
     *     @OA\Response(response=404, description="Jugador no encontrado")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $jugador = $this->jugadorService->findById($id);

        if (!$jugador) {
            return response()->json(['message' => 'Jugador no encontrado'], 404);
        }

        return response()->json($jugador, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/jugadores/{dni}",
     *     tags={"Jugadores"},
     *     summary="Obtener un jugador por DNI",
     *     description="Busca un jugador por su DNI único.",
     *     @OA\Parameter(
     *         name="dni",
     *         in="path",
     *         description="DNI del jugador",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Jugador encontrado"),
     *     @OA\Response(response=404, description="Jugador no encontrado")
     * )
     */
    public function showByDni(string $dni): JsonResponse
    {
        $jugador = $this->jugadorService->findByDni($dni);

        if (!$jugador) {
            return response()->json(['message' => 'Jugador no encontrado'], 404);
        }

        return response()->json($jugador, 200);
    }

    /**
     * Actualizar un jugador.
     * 
     * @OA\Put(
     *     path="/api/jugadores/{id}",
     *     tags={"Jugadores"},
     *     summary="Actualizar un jugador",
     *     description="Permite modificar la información de un jugador ya existente.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del jugador a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", example="Juan Pérez"),
     *             @OA\Property(property="dni", type="string", example="12345678"),
     *             @OA\Property(property="habilidad", type="integer", example=85),
     *             @OA\Property(property="fuerza", type="integer", example=80, nullable=true),
     *             @OA\Property(property="velocidad", type="integer", example=90, nullable=true),
     *             @OA\Property(property="reaccion", type="integer", example=70, nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Jugador actualizado correctamente"),
     *     @OA\Response(response=404, description="Jugador no encontrado")
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'dni' => 'sometimes|string|unique:jugadores,dni,' . $id,
            'habilidad' => 'sometimes|integer|min:0|max:100',
            'fuerza' => 'nullable|integer|min:0|max:100',
            'velocidad' => 'nullable|integer|min:0|max:100',
            'reaccion' => 'nullable|integer|min:0|max:100',
        ]);

        $actualizado = $this->jugadorService->update($id, $data);

        if (!$actualizado) {
            return response()->json(['message' => 'Jugador no encontrado'], 404);
        }

        return response()->json(['message' => 'Jugador actualizado correctamente'], 200);
    }

    /**
     * Eliminar un jugador.
     * 
     * @OA\Delete(
     *     path="/api/jugadores/{id}",
     *     tags={"Jugadores"},
     *     summary="Eliminar un jugador",
     *     description="Realiza la eliminación lógica de un jugador por ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del jugador a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Jugador eliminado correctamente"),
     *     @OA\Response(response=404, description="Jugador no encontrado")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $eliminado = $this->jugadorService->delete($id);

        if (!$eliminado) {
            return response()->json(['message' => 'Jugador no encontrado'], 404);
        }

        return response()->json(['message' => 'Jugador eliminado correctamente'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/jugadores/{id}/torneos",
     *     tags={"Jugadores"},
     *     summary="Obtener torneos en los que participa un jugador",
     *     description="Obtiene la lista de torneos en los que ha participado un jugador. Se puede filtrar por torneos ganados.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del jugador",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="ganados",
     *         in="query",
     *         description="Filtrar solo torneos ganados (true, false, todos)",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response=200, description="Lista de torneos del jugador"),
     *     @OA\Response(response=404, description="Jugador no encontrado o sin torneos")
     * )
     */
    public function torneos(int $id, Request $request): JsonResponse
    {
        $soloGanados = $request->query('ganados', false);

        $torneos = $this->jugadorService->getTorneos($id, $soloGanados);

        if (empty($torneos)) {
            return response()->json(['message' => 'Jugador no encontrado o sin torneos'], 404);
        }

        return response()->json($torneos, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/jugadores/{id}/partidas",
     *     tags={"Jugadores"},
     *     summary="Obtener partidas en las que participa un jugador",
     *     description="Obtiene la lista de partidas en las que ha jugado un jugador, filtrando por ganadas, perdidas o todas.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del jugador",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="filtro",
     *         in="query",
     *         description="Filtrar por tipo de partida (ganadas, perdidas, todas)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Lista de partidas del jugador"),
     *     @OA\Response(response=404, description="Jugador no encontrado o sin partidas")
     * )
     */
    public function partidas(Request $request, int $id): JsonResponse
    {
        $filtro = $request->query('filtro', 'todas');

        $partidas = $this->jugadorService->getPartidas($id, $filtro);

        if (empty($partidas)) {
            return response()->json(['message' => 'Jugador no encontrado o sin partidas'], 404);
        }

        return response()->json($partidas, 200);
    }
}
