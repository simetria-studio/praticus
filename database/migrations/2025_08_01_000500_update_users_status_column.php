<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Primeiro, alterar a coluna para aceitar todos os valores
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'suspended', 'ativo', 'inativo'])->change();
        });

        // Depois, atualizar os dados existentes
        DB::table('users')->where('status', 'active')->update(['status' => 'ativo']);
        DB::table('users')->where('status', 'inactive')->update(['status' => 'inativo']);
        DB::table('users')->where('status', 'suspended')->update(['status' => 'inativo']);

        // Finalmente, alterar para aceitar apenas os novos valores
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['ativo', 'inativo'])->change();
        });
    }

    public function down(): void
    {
        // Primeiro, alterar para aceitar todos os valores
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'suspended', 'ativo', 'inativo'])->change();
        });

        // Depois, reverter os dados
        DB::table('users')->where('status', 'ativo')->update(['status' => 'active']);
        DB::table('users')->where('status', 'inativo')->update(['status' => 'inativo']);

        // Finalmente, alterar para os valores originais
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'suspended'])->change();
        });
    }
};
