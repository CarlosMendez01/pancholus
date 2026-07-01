<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoInsumo extends Model
{
    /** @use HasFactory<\Database\Factories\PedidoInsumoFactory> */
    use HasFactory;

    protected $fillable = [
        'fecha',
        'estado',
        'observacion',
        'foodtruck_id',
        'user_id',
    ];

    public function foodtruck() {
        return $this->belongsTo(Foodtruck::class);
    }

    public function usuario() {
        return $this->belongsTo(User::class);
    }
}
