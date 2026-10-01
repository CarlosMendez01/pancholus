<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodTruckStock extends Model
{
    /** @use HasFactory<\Database\Factories\FoodTruckStockFactory> */
    use HasFactory;
    protected $fillable = ['food_truck_id', 'ingredient_id', 'quantity'];
}
