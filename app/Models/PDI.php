<?php

namespace App\Models;

use Database\Factories\PDIFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PDI extends Model
{
    /** @use HasFactory<PDIFactory> */
    use HasFactory;

    protected $fillable = ['food_truck_id', 'status'];

    public function foodTruck(): BelongsTo
    {
        return $this->belongsTo(FoodTruck::class);
    }

    /**
     * Comidas incluidas en este pedido interno.
     */
    public function details(): HasMany
    {
        return $this->hasMany(PdiDetail::class, 'PDI_id');
    }
}
