<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Alterar a coluna role para aceitar 'coordinator'
            $table->enum('role', ['admin', 'manager', 'operator', 'coordinator'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverter para os valores originais
            $table->enum('role', ['admin', 'manager', 'operator'])->change();
        });
    }
};
