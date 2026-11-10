<?php

namespace App\Models;

use Database\Factories\MealFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meal extends Model
{
    /** @use HasFactory<MealFactory> */
    use HasFactory, SoftDeletes;

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

    /**
     * Relación con los detalles de pedidos internos que incluyen esta comida.
     */
    public function pdiDetails(): HasMany
    {
        return $this->hasMany(PdiDetail::class);
    }

    /**
     * Insumos que componen la receta de esta comida.
     */
    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    /**
     * Ingredientes de la comida a través de la tabla `recipes`.
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'recipes')
            ->withPivot('required_quantity')
            ->withTimestamps();
    }
}
