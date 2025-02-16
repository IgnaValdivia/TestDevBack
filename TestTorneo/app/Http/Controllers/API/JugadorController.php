<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\JugadorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JugadorController extends Controller
{

    private JugadorService $jugadorService;

    public function __construct(JugadorService $jugadorService)
    {
        $this->jugadorService = $jugadorService;
    }

    /**
     * Obtener todos los jugadores.
     * Filtros (genero: Masculino - Femenino - Todos)
     */
    public function index(Request $request): JsonResponse
    {
        $jugadores = $this->jugadorService->getAll($request->query('genero'));

        return response()->json($jugadores, 200);
    }

    /**
     * Crear un nuevo jugador.
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
     * Obtener un jugador por DNI.
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
     * Obtener torneos en los que participa un jugador.
     * Filtros: (ganados: true - false - todos)
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
     * Obtener partidas en los que participa un jugador.
     * Filtros: (filtro: ganadas - perdidas -todas)
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
