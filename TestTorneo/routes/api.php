<?php

use App\Http\Controllers\API\JugadorController;
use App\Http\Controllers\API\PartidaController;
use App\Http\Controllers\API\TorneoController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {

    Route::prefix('torneos')->group(function () {
        Route::get('/', [TorneoController::class, 'index']);  // Obtener todos los torneos
        Route::get('{id}', [TorneoController::class, 'show']); // Obtener torneo por ID
        Route::post('/', [TorneoController::class, 'store']); // Crear torneo
        Route::put('{id}', [TorneoController::class, 'update']); // Actualizar torneo
        Route::delete('{id}', [TorneoController::class, 'destroy']); // Eliminar torneo
        Route::get('{id}/partidas', [TorneoController::class, 'partidas']); // Obtener partidas de un torneo
        Route::post('{id}/asignar-jugadores', [TorneoController::class, 'asignarJugadores']); //asignar jugadores a torneo
        Route::post('{id}/comenzar', [TorneoController::class, 'comenzarTorneo']); //comenzar un torneo
        Route::get('{id}/estado', [TorneoController::class, 'estadoTorneo']); //obtener estado de un torneo
        Route::get('{id}/ronda/{ronda}', [TorneoController::class, 'partidasPorRonda']); //obtener partidas de cierta ronda de un torneo
    });

    Route::prefix('jugadores')->group(function () {
        Route::get('/', [JugadorController::class, 'index']); // Obtener todos los jugadores
        Route::get('{id}', [JugadorController::class, 'show']); // Obtener jugador por ID
        Route::get('{dni}', [JugadorController::class, 'showByDni']); // Obtener jugador por DNI
        Route::post('/', [JugadorController::class, 'store']); // Crear jugador
        Route::put('{id}', [JugadorController::class, 'update']); // Actualizar jugador
        Route::delete('{id}', [JugadorController::class, 'destroy']); // Eliminar jugador
        Route::get('{id}/torneos', [JugadorController::class, 'torneos']); // Torneos en los que participa un jugador (filtros generales - ganados)
        Route::get('{id}/partidas', [JugadorController::class, 'partidas']); //obtener las partidas jugadas del jugador (filtros ganadas - perdidas)
    });

    Route::prefix('partidas')->group(function () {
        Route::get('{id}', [PartidaController::class, 'show'])->name('partidas.show')->middleware('validate.id');
    });
});
