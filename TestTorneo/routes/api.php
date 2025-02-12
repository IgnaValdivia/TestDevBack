<?php

use App\Http\Controllers\API\JugadorController;
use App\Http\Controllers\API\PartidaController;
use App\Http\Controllers\API\TorneoController;
use Illuminate\Support\Facades\Route;

Route::prefix('torneos')->group(function () {
    Route::get('/', [TorneoController::class, 'index']);  // Obtener todos los torneos
    Route::get('{id}', [TorneoController::class, 'show']); // Obtener torneo por ID
    Route::post('/', [TorneoController::class, 'store']); // Crear torneo
    Route::put('{id}', [TorneoController::class, 'update']); // Actualizar torneo
    Route::delete('{id}', [TorneoController::class, 'destroy']); // Eliminar torneo
    Route::get('{id}/partidas', [TorneoController::class, 'partidas']); // Obtener partidas de un torneo
});

Route::prefix('jugadores')->group(function () {
    Route::get('/', [JugadorController::class, 'index']); // Obtener todos los jugadores
    Route::get('{id}', [JugadorController::class, 'show']); // Obtener jugador por ID
    Route::post('/', [JugadorController::class, 'store']); // Crear jugador
    Route::put('{id}', [JugadorController::class, 'update']); // Actualizar jugador
    Route::delete('{id}', [JugadorController::class, 'destroy']); // Eliminar jugador
    Route::get('{id}/torneos', [JugadorController::class, 'torneos']); // Torneos en los que participa un jugador
});

Route::prefix('partidas')->group(function () {
    Route::get('/', [PartidaController::class, 'index']); // Obtener todas las partidas
    Route::get('{id}', [PartidaController::class, 'show']); // Obtener partida por ID
    Route::post('/', [PartidaController::class, 'store']); // Crear partida
    Route::put('{id}', [PartidaController::class, 'update']); // Actualizar partida
    Route::delete('{id}', [PartidaController::class, 'destroy']); // Eliminar partida
    Route::post('{id}/determinar-ganador', [PartidaController::class, 'determinarGanador']); // Determinar ganador
});
