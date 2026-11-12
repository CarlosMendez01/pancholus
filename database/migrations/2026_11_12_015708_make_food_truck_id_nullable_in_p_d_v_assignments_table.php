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
        Schema::table('p_d_v_assignments', function (Blueprint $table) {
            $table->foreignId('food_truck_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('p_d_v_assignments', function (Blueprint $table) {
            $table->foreignId('food_truck_id')->nullable(false)->change();
        });
    }
};
