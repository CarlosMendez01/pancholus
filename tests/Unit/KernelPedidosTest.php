<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Services\KernelPedidos;
use PHPUnit\Framework\TestCase;

/**
 * Pruebas unitarias del kernel de Pedidos.
 *
 * Son aisladas: no inician Laravel ni usan base de datos. Cada caso
 * prepara su objeto, ejecuta un método y compara esperado con real.
 */
class KernelPedidosTest extends TestCase
{
    private KernelPedidos $kernel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->kernel = new KernelPedidos;
    }

    public function test_estados_permitidos_son_los_tres_del_kernel(): void
    {
        // Esperado: la lista de estados que admite el sistema.
        $this->assertSame(
            [Order::STATUS_PENDIENTE, Order::STATUS_REALIZADO, Order::STATUS_CANCELADO],
            $this->kernel->estadosPermitidos()
        );
    }

    public function test_acepta_un_estado_valido(): void
    {
        // Entrada válida: "realizado".
        $this->assertTrue($this->kernel->esEstadoValido(Order::STATUS_REALIZADO));
    }

    public function test_rechaza_un_estado_invalido(): void
    {
        // Entrada inválida: "enviado" no existe en el kernel.
        $this->assertFalse($this->kernel->esEstadoValido('enviado'));
    }

    public function test_rechaza_un_estado_vacio(): void
    {
        // Entrada inválida: cadena vacía.
        $this->assertFalse($this->kernel->esEstadoValido(''));
    }

    public function test_rechaza_un_estado_nulo(): void
    {
        // Entrada inválida: sin valor.
        $this->assertFalse($this->kernel->esEstadoValido(null));
    }

    public function test_normaliza_un_estado_valido_conservandolo(): void
    {
        // Esperado: el mismo estado recibido.
        $this->assertSame(
            Order::STATUS_CANCELADO,
            $this->kernel->normalizarFiltro(Order::STATUS_CANCELADO)
        );
    }

    public function test_normaliza_un_estado_invalido_a_pendiente(): void
    {
        // Esperado: un valor fuera de la lista cae a pendiente.
        $this->assertSame(
            Order::STATUS_PENDIENTE,
            $this->kernel->normalizarFiltro('enviado')
        );
    }

    public function test_conserva_el_filtro_todos(): void
    {
        // Esperado: "todos" es un filtro válido del listado.
        $this->assertSame(
            KernelPedidos::FILTRO_TODOS,
            $this->kernel->normalizarFiltro(KernelPedidos::FILTRO_TODOS)
        );
    }

    public function test_solo_un_pedido_pendiente_se_puede_modificar(): void
    {
        $this->assertTrue($this->kernel->puedeModificar(Order::STATUS_PENDIENTE));
    }

    public function test_un_pedido_realizado_o_cancelado_no_se_puede_modificar(): void
    {
        $this->assertFalse($this->kernel->puedeModificar(Order::STATUS_REALIZADO));
        $this->assertFalse($this->kernel->puedeModificar(Order::STATUS_CANCELADO));
    }

    public function test_un_pedido_finalizado_se_puede_eliminar(): void
    {
        $this->assertTrue($this->kernel->puedeEliminar(Order::STATUS_REALIZADO));
        $this->assertTrue($this->kernel->puedeEliminar(Order::STATUS_CANCELADO));
    }

    public function test_un_pedido_pendiente_no_se_puede_eliminar(): void
    {
        $this->assertFalse($this->kernel->puedeEliminar(Order::STATUS_PENDIENTE));
    }

    public function test_acumula_una_cantidad_en_un_carrito_vacio(): void
    {
        // Preparar: carrito vacío. Ejecutar: agregar 2 de la comida 5.
        $carrito = $this->kernel->acumularCarrito([], 5, 2);

        // Comprobar: la comida 5 quedó con cantidad 2.
        $this->assertSame([5 => 2], $carrito);
    }

    public function test_acumula_sobre_una_cantidad_existente(): void
    {
        // Preparar: la comida 5 ya tenía 2. Ejecutar: agregar 3 más.
        $carrito = $this->kernel->acumularCarrito([5 => 2], 5, 3);

        // Comprobar: se suman las cantidades.
        $this->assertSame([5 => 5], $carrito);
    }

    public function test_crea_una_entrada_separada_para_otra_comida(): void
    {
        // Preparar: la comida 5 con 2. Ejecutar: agregar 1 de la comida 7.
        $carrito = $this->kernel->acumularCarrito([5 => 2], 7, 1);

        // Comprobar: cada comida mantiene su propia entrada.
        $this->assertSame([5 => 2, 7 => 1], $carrito);
    }

    public function test_no_modifica_el_carrito_original(): void
    {
        // Preparar: un carrito de referencia.
        $original = [5 => 2];

        $this->kernel->acumularCarrito($original, 5, 3);

        // Esperado: el array original queda intacto.
        $this->assertSame([5 => 2], $original);
    }
}
