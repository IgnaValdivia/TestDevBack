<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Interfaces\Repositories\IJugadorFemeninoRepository;
use App\Interfaces\Repositories\IJugadorMasculinoRepository;
use Illuminate\Http\Request;

class JugadorController extends Controller
{

    private IJugadorMasculinoRepository $jugadorMasculinoRepository;
    private IJugadorFemeninoRepository $jugadorFemeninoRepository;

    public function __construct(IJugadorMasculinoRepository $masculinoRepo, IJugadorFemeninoRepository $femeninoRepo)
    {
        $this->jugadorMasculinoRepository = $masculinoRepo;
        $this->jugadorFemeninoRepository = $femeninoRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'masculinos' => $this->jugadorMasculinoRepository->getAll(),
            'femeninos' => $this->jugadorFemeninoRepository->getAll()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'genero' => 'required|string|in:Masculino,Femenino',
            'habilidad' => 'required|integer'
        ]);

        $jugador = $request->genero === 'Masculino'
            ? $this->jugadorMasculinoRepository->create($request->all())
            : $this->jugadorFemeninoRepository->create($request->all());

        return response()->json($jugador, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jugador = $this->jugadorMasculinoRepository->findById($id) ??
            $this->jugadorFemeninoRepository->findById($id);

        if (!$jugador) {
            return response()->json(['error' => 'Jugador no encontrado'], 404);
        }

        return response()->json($jugador);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jugador = $this->jugadorMasculinoRepository->findById($id) ??
            $this->jugadorFemeninoRepository->findById($id);

        if (!$jugador) {
            return response()->json(['error' => 'Jugador no encontrado'], 404);
        }

        $this->jugadorMasculinoRepository->update($id, $request->all());
        return response()->json(['message' => 'Jugador actualizado correctamente']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (
            !$this->jugadorMasculinoRepository->delete($id) &&
            !$this->jugadorFemeninoRepository->delete($id)
        ) {
            return response()->json(['error' => 'Jugador no encontrado'], 404);
        }

        return response()->json(['message' => 'Jugador eliminado correctamente']);
    }
}
