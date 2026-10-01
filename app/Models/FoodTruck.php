<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodTruck extends Model
{
    /** @use HasFactory<\Database\Factories\FoodTruckFactory> */
    use HasFactory;
    protected $fillable = ['license_plate', 'status'];
}
