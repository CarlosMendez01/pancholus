<?php

namespace App\Models;

use Database\Factories\FoodTruckAssignmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoodTruckAssignment extends Model
{
    /** @use HasFactory<FoodTruckAssignmentFactory> */
    use HasFactory;

    protected $fillable = ['food_truck_id', 'user_id'];

    public function foodTruck(): BelongsTo
    {
        return $this->belongsTo(FoodTruck::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
