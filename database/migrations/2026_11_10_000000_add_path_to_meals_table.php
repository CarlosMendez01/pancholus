<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * La columna `path` se agregó a la migración original de `meals` después de
 * haberse ejecutado en bases existentes. Esta migración la agrega de forma
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
            if (! Schema::hasColumn('meals', 'path')) {
                $table->string('path')->nullable()->after('details');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meals', function (Blueprint $table) {
            if (Schema::hasColumn('meals', 'path')) {
                $table->dropColumn('path');
            }
        });
    }
};
