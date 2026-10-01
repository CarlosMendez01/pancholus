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
        Schema::create('p_d_i_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_truck_id')->constrained(table: 'food_trucks');
            $table->enum('status', ['pendiente', 'realizado', 'cancelado']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_d_i_s');
    }
};
