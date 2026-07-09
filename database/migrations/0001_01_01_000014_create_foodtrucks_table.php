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
        Schema::create('foodtrucks', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('patente')->unique();
            $table->foreignId('estado_foodtruck_id')
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();
            $table->foreignId('punto_venta_id')
                  ->constrained('punto_ventas')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foodtrucks');
    }
};
