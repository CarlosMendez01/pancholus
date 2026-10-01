<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodTruckAssignment extends Model
{
    /** @use HasFactory<\Database\Factories\FoodTruckAssignmentFactory> */
    use HasFactory;
    protected $fillable = ['food_truck_id', 'user_id'];
}
