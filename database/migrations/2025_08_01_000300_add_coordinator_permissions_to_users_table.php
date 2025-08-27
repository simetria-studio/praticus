<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Adiciona coordinator_id se não existir
        if (!Schema::hasColumn('users', 'coordinator_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('coordinator_id')->nullable()->constrained()->nullOnDelete();
            });
        }

        // Adiciona permissions se não existir
        if (!Schema::hasColumn('users', 'permissions')) {
            Schema::table('users', function (Blueprint $table) {
                $table->json('permissions')->nullable();
            });
        }

        // Adiciona is_coordinator se não existir
        if (!Schema::hasColumn('users', 'is_coordinator')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_coordinator')->default(false);
            });
        }

        // Adiciona last_school_access se não existir
        if (!Schema::hasColumn('users', 'last_school_access')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('last_school_access')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Remoção segura: verifica antes
        if (Schema::hasColumn('users', 'coordinator_id')) {
            Schema::table('users', function (Blueprint $table) {
                // Tenta dropar a FK se existir
                try {
                    $table->dropForeign(['coordinator_id']);
                } catch (\Throwable $e) {
                    // ignora se não existir FK
                }
                $table->dropColumn('coordinator_id');
            });
        }

        if (Schema::hasColumn('users', 'permissions')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('permissions');
            });
        }

        if (Schema::hasColumn('users', 'is_coordinator')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_coordinator');
            });
        }

        if (Schema::hasColumn('users', 'last_school_access')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('last_school_access');
            });
        }
    }
};
