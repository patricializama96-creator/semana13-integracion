<?php

namespace Tests\Feature;

use App\Models\Comercio;
use App\Models\Transaccion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoverEstadoTest extends TestCase
{
    use RefreshDatabase;

    private function crearTransaccion(string $estado): Transaccion
    {
        $comercio = Comercio::create([
            'nombre_comercio' => 'Comercio de prueba',
            'rubro'           => 'Restaurante',
            'telefono'        => '2222-0000',
        ]);

        return Transaccion::create([
            'comercio_id'    => $comercio->id,
            'monto'          => 10,
            'cliente_nombre' => 'Cliente de prueba',
            'estado'         => $estado,
        ]);
    }

    public function test_completada_a_fallida_se_bloquea(): void
    {
        $t = $this->crearTransaccion('Completada');

        $this->patch(route('transacciones.mover', [$t, 'Fallida']))
             ->assertStatus(400);

        $this->assertSame('Completada', $t->fresh()->estado);
    }

    public function test_estado_inventado_se_bloquea(): void
    {
        $t = $this->crearTransaccion('Iniciada');

        $this->patch(route('transacciones.mover', [$t, 'Cancelada']))
             ->assertStatus(400);
    }

    public function test_transicion_valida_funciona(): void
    {
        $t = $this->crearTransaccion('Iniciada');

        $this->patch(route('transacciones.mover', [$t, 'Completada']))
             ->assertRedirect();

        $this->assertSame('Completada', $t->fresh()->estado);
    }
}
