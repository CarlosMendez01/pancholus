<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    /** @use HasFactory<\Database\Factories\StockFactory> */
    use HasFactory;

    protected $fillable = [
        'cantidad_disponible',
        'stock_minimo',
        'producto_id',
        'deposito_id',
    ];

    public function producto() {
        return $this->belongsTo(Producto::class);
    }

    public function deposito() {
        return $this->belongsTo(Deposito::class);
    }
}
