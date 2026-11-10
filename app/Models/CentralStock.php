<?php

namespace App\Models;

use Database\Factories\CentralStockFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CentralStock extends Model
{
    /** @use HasFactory<CentralStockFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['product_id', 'quantity'];

    /**
     * Ingrediente al que corresponde este stock.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class, 'product_id');
    }
}
