<?php

namespace App\Models;

use Database\Factories\PDVFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PDV extends Model
{
    /** @use HasFactory<PDVFactory> */
    use HasFactory;

    protected $fillable = ['latitude', 'longitude'];

    /**
     * Asignaciones de este punto de venta a un food truck.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(PDV_assignment::class, 'PDV_id');
    }
}
