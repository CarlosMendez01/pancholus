<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposito extends Model
{
    /** @use HasFactory<\Database\Factories\DepositoFactory> */
    use HasFactory;

    protected $fillable = [
        'nombre',
        'ubicacion',
    ];

    public function stocks(){
        return $this->hasMany(Stock::class);
    }
}
