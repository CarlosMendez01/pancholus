<?php

namespace App\Models;

use Database\Factories\FoodTruckFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodTruck extends Model
{
    /** @use HasFactory<FoodTruckFactory> */
    use HasFactory, SoftDeletes;

    /**
     * Cantidad máxima de empleados que pueden asignarse a un food truck.
     */
    public const MAX_EMPLEADOS = 3;

    protected $fillable = ['license_plate', 'status'];

    /**
     * Pedidos internos (PDI) generados para este food truck.
     */
    public function pdis(): HasMany
    {
        return $this->hasMany(PDI::class);
    }

    /**
     * Asignaciones de empleados a este food truck.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(FoodTruckAssignment::class);
    }

    /**
     * Puntos de venta asignados a este food truck.
     */
    public function pdvAssignments(): HasMany
    {
        return $this->hasMany(PDV_assignment::class, 'food_truck_id');
    }

    /**
     * Stock de ingredientes disponible en este food truck.
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(FoodTruckStock::class);
    }
}
