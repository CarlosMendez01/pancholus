<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    /** @use HasFactory<\Database\Factories\PedidoFactory> */
    use HasFactory;

    protected $fillable = [
        'fecha',
        'hora',
        'total',
        'estado_pedido_id',
        'cliente_id',
        'user_id',
    ];

    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }

    public function usuario() {
        return $this->belongsTo(User::class);
    }

    public function detallesPedido() {
        return $this->hasMany(DetallePedido::class);
    }

    public function estadoPedido() {
        return $this->belongsTo(EstadoPedido::class);
    }
}
