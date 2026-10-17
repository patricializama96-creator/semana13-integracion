<?php

use App\Http\Controllers\Api\TransaccionApiController;
use App\Http\Resources\ComercioResource;
use App\Models\Comercio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/comercios', function () {
    return ComercioResource::collection(Comercio::all());
});

Route::get('/comercios/{comercio}', function (Comercio $comercio) {
    return new ComercioResource($comercio->load('transacciones'));
});

// Semana 13 · Lab 6: 5 rutas REST para transacciones
Route::apiResource('transacciones', TransaccionApiController::class)
    ->parameters(['transacciones' => 'transaccion']);
