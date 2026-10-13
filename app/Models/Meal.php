<?php

namespace App\Models;

use Database\Factories\MealFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meal extends Model
{
    /** @use HasFactory<MealFactory> */
    use HasFactory;

    protected $fillable = ['name', 'price', 'details', 'filter_id'];

    public function filter()
    {
        return $this->belongsTo(Filter::class);
    }

    /**
     * Relación con los detalles de pedido que incluyen esta comida.
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
