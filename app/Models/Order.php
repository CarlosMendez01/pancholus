<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    public const STATUS_PENDIENTE = 'pendiente';

    public const STATUS_REALIZADO = 'realizado';

    public const STATUS_CANCELADO = 'cancelado';

    protected $fillable = ['user_id', 'status'];

    /**
     * Relación con el usuario (cliente) que realizó el pedido.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el detalle de comidas del pedido.
     */
    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
