<?php

namespace App\Models;

use Database\Factories\OrderDetailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    /** @use HasFactory<OrderDetailFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['order_id', 'meal_id', 'quantity'];

    /**
     * Relación con el pedido al que pertenece el detalle.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación con la comida solicitada.
     */
    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }
}
