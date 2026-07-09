<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoPedidoInsumo extends Model
{
    /** @use HasFactory<\Database\Factories\EstadoPedidoInsumoFactory> */
    use HasFactory;

    protected $fillable = [
        'descripcion',
    ];

    public function pedidoInsumo() {
        return $this->hasMany(PedidoInsumo::class);
    }
}
