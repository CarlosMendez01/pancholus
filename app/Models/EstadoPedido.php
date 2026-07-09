<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoPedido extends Model
{
    /** @use HasFactory<\Database\Factories\EstadoPedidoFactory> */
    use HasFactory;

    protected $fillable = [
        'descripcion',
    ];

    public function pedidos() {
        return $this->hasMany(Pedido::class);
    }
}
