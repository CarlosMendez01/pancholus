<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    /** @use HasFactory<\Database\Factories\MealFactory> */
    use HasFactory;
    protected $fillable = ['name', 'price', 'details', 'filter_id'];

    public function filter()
    {
        return $this->belongsTo(Filter::class);
    }
}
