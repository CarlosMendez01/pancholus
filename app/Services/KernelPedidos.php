<?php

namespace App\Services;

use App\Models\Order;

/**
 * Kernel de Pedidos: concentra las reglas de estado y del carrito.
 *
 * Es una clase pura (sin Laravel ni base de datos) para poder probarla
 * de forma aislada. El controlador y los Form Requests la usan como
 * única fuente de verdad.
 */
class KernelPedidos
{
    /**
     * Valor de filtro que representa "todos los estados".
     */
    public const FILTRO_TODOS = 'todos';

    /**
     * Estados válidos de un pedido.
     *
     * @return array<int, string>
     */
    public function estadosPermitidos(): array
    {
        return [
            Order::STATUS_PENDIENTE,
            Order::STATUS_REALIZADO,
            Order::STATUS_CANCELADO,
        ];
    }

    /**
     * Indica si el estado recibido es uno de los estados permitidos.
     */
    public function esEstadoValido(?string $estado): bool
    {
        return in_array($estado, $this->estadosPermitidos(), true);
    }

    /**
     * Normaliza el estado usado como filtro en el listado del empleado.
     *
     * Un estado permitido se conserva; "todos" se conserva; cualquier
     * otro valor (incluido null) cae al estado pendiente.
     */
    public function normalizarFiltro(?string $filtro): string
    {
        if ($filtro === self::FILTRO_TODOS) {
            return self::FILTRO_TODOS;
        }

        return $this->esEstadoValido($filtro) ? $filtro : Order::STATUS_PENDIENTE;
    }

    /**
     * Solo los pedidos pendientes pueden cambiar de estado.
     */
    public function puedeModificar(string $estado): bool
    {
        return $estado === Order::STATUS_PENDIENTE;
    }

    /**
     * Solo los pedidos finalizados (realizados o cancelados) pueden darse de baja.
     */
    public function puedeEliminar(string $estado): bool
    {
        return in_array($estado, [Order::STATUS_REALIZADO, Order::STATUS_CANCELADO], true);
    }

    /**
     * Acumula una cantidad en el carrito de la sesión.
     *
     * Si la comida ya estaba en el carrito, se suman las cantidades;
     * si no, se crea la entrada. Devuelve un carrito nuevo sin modificar
     * el original.
     *
     * @param  array<int, int>  $carrito
     * @return array<int, int>
     */
    public function acumularCarrito(array $carrito, int $mealId, int $cantidad): array
    {
        $carrito[$mealId] = ($carrito[$mealId] ?? 0) + $cantidad;

        return $carrito;
    }
}
