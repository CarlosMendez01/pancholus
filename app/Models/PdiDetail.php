<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdiDetail extends Model
{
    /** @use HasFactory<\Database\Factories\PdiDetailFactory> */
    use HasFactory;
    protected $fillable = ['PDI_id', 'meal_id', 'quantity'];
}
