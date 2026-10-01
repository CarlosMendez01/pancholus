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
        Schema::create('pdi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('PDI_id')->constrained(table: 'p_d_i_s');
            $table->foreignId('meal_id')->constrained(table: 'meals');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pdi_details');
    }
};
