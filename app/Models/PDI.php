<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PDI extends Model
{
    /** @use HasFactory<\Database\Factories\PDIFactory> */
    use HasFactory;
    protected $fillable = ['food_truck_id', 'status'];
}
