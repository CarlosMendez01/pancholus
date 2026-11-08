<?php

namespace App\Models;

use Database\Factories\PDVAssignmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PDV_assignment extends Model
{
    /** @use HasFactory<PDVAssignmentFactory> */
    use HasFactory;

    protected $fillable = ['PDV_id', 'food_truck_id'];

    public function pdv(): BelongsTo
    {
        return $this->belongsTo(PDV::class, 'PDV_id');
    }

    public function foodTruck(): BelongsTo
    {
        return $this->belongsTo(FoodTruck::class);
    }
}
