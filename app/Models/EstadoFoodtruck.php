<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoFoodtruck extends Model
{
    /** @use HasFactory<\Database\Factories\EstadoFoodtruckFactory> */
    use HasFactory;

    protected $fillable = [
        'descripcion',
    ];

    public function foodtrucks() {
        return $this->hasMany(Foodtruck::class);
    }
}
