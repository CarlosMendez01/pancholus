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
        Schema::create('food_truck_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_truck_id')->constrained(table: 'food_trucks');
            $table->foreignId('user_id')->constrained(table: 'users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_truck_assignments');
    }
};
