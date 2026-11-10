<?php

namespace App\Models;

use Database\Factories\FilterFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Filter extends Model
{
    /** @use HasFactory<FilterFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
