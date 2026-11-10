<?php

namespace App\Models;

use Database\Factories\PdiDetailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PdiDetail extends Model
{
    /** @use HasFactory<PdiDetailFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['PDI_id', 'meal_id', 'quantity'];

    public function pdi(): BelongsTo
    {
        return $this->belongsTo(PDI::class, 'PDI_id');
    }

    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }
}
