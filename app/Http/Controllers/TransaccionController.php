<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarTransaccionRequest;
use App\Http\Resources\TransaccionResource;
use App\Models\Transaccion;

class TransaccionApiController extends Controller
{
    public function index()
    {
        return TransaccionResource::collection(Transaccion::latest()->get());
    }

    public function show(Transaccion $transaccion)
    {
        return new TransaccionResource($transaccion);
    }

    public function store(GuardarTransaccionRequest $request)
    {
        $datos = $request->validated();
        $datos['estado'] = $request->input('estado', 'Iniciada');

        $transaccion = Transaccion::create($datos);

        return (new TransaccionResource($transaccion))
            ->response()
            ->setStatusCode(201);
    }

    public function destroy(Transaccion $transaccion)
    {
        $transaccion->delete();

        return response()->noContent(); // 204
    }
}
