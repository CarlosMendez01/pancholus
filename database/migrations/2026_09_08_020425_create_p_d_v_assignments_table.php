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
        Schema::create('p_d_v_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('PDV_id')->constrained(table: 'p_d_v_s');
             $table->foreignId('food_truck_id')->constrained(table: 'food_trucks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_d_v_assignments');
    }
};
