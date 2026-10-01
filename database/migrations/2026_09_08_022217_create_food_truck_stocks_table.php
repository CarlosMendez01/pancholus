<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('food_truck_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_truck_id')->constrained(table: 'food_trucks');
            $table->foreignId('ingredient_id')->constrained(table: 'ingredients');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_truck_stocks');
    }
};
