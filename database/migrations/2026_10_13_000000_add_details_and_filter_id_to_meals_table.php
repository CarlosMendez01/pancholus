<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * La migración original de `meals` se editó después de haberse ejecutado
 * (se le agregaron `details` y `filter_id`), por lo que las bases de datos
 * existentes quedaron sin esas columnas. Esta migración las agrega de forma
 * idempotente para las instalaciones que ya venían de antes.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('meals', function (Blueprint $table) {
            if (! Schema::hasColumn('meals', 'details')) {
                $table->string('details')->nullable()->after('price');
            }

            if (! Schema::hasColumn('meals', 'filter_id')) {
                $table->foreignId('filter_id')->nullable()->after('details')->constrained(table: 'filters');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meals', function (Blueprint $table) {
            if (Schema::hasColumn('meals', 'filter_id')) {
                $table->dropConstrainedForeignId('filter_id');
            }

            if (Schema::hasColumn('meals', 'details')) {
                $table->dropColumn('details');
            }
        });
    }
};
