<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->nullable()->after('name');
            $table->string('dni')->unique()->after('last_name');
            $table->string('phone')->nullable()->after('dni');
            $table->foreignId('role_id')->after('password')->constrained(table: 'rols');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropSoftDeletes();
            $table->dropColumn(['last_name', 'dni', 'phone', 'role_id']);
        });
    }
};
