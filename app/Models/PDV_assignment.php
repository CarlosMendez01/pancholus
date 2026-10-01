<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PDV_assignment extends Model
{
    /** @use HasFactory<\Database\Factories\PDVAssignmentFactory> */
    use HasFactory;
    protected $fillable = ['PDV_id', 'food_truck_id'];
}
