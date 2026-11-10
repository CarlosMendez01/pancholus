<?php

namespace App\Models;

use Database\Factories\IngredientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    /** @use HasFactory<IngredientFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'price', 'expiration_date'];

    /**
     * Recetas (comidas) en las que se usa este ingrediente.
     */
    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    /**
     * Comidas que usan este ingrediente a través de la tabla `recipes`.
     */
    public function meals(): BelongsToMany
    {
        return $this->belongsToMany(Meal::class, 'recipes')
            ->withPivot('required_quantity')
            ->withTimestamps();
    }

    /**
     * Stock central de este ingrediente.
     */
    public function centralStock(): HasOne
    {
        return $this->hasOne(CentralStock::class, 'product_id');
    }

    /**
     * Stock de este ingrediente en cada food truck.
     */
    public function foodTruckStocks(): HasMany
    {
        return $this->hasMany(FoodTruckStock::class);
    }
}
