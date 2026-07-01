<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foodtruck extends Model
{
    /** @use HasFactory<\Database\Factories\FoodtruckFactory> */
    use HasFactory;

    protected $fillable = [
        'patente',
        'estado',
        'capacidad',
        'punto_venta_id',
    ];

    public function puntoVenta() {
        return $this->belongsTo(PuntoVenta::class);
    }

    public function pedidosInsumo() {
        return $this->hasMany(PedidoInsumo::class);
    }
}
