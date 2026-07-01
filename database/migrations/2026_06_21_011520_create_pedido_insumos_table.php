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
        Schema::create('pedido_insumos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->enum('estado', [
                'pendiente',
                'aprobado',
                'enviado',
                'entregado',
                'cancelado'
            ])->default('pendiente');
            $table->text('observacion')->nullable();
            $table->foreignId('foodtruck_id')
                  ->constrained('foodtrucks')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained()
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
        Schema::dropIfExists('pedido_insumos');
    }
};
