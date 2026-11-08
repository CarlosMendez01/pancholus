<?php

namespace App\Models;

use Database\Factories\FoodTruckStockFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoodTruckStock extends Model
{
    /** @use HasFactory<FoodTruckStockFactory> */
    use HasFactory;

    protected $fillable = ['food_truck_id', 'ingredient_id', 'quantity'];

    public function foodTruck(): BelongsTo
    {
        return $this->belongsTo(FoodTruck::class);
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }
}
