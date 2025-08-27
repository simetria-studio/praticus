<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ex: 1º Ano A
            $table->string('code')->unique(); // Ex: 1A2025
            $table->string('grade'); // Ex: 1º Ano, 2º Ano, etc.
            $table->enum('period', ['manha', 'tarde', 'noite'])->default('manha');
            $table->year('year');
            $table->unsignedInteger('capacity')->nullable();
            $table->enum('status', ['ativa', 'inativa'])->default('ativa');
            $table->text('description')->nullable();

            // Relações
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('coordinator_id')->nullable()->constrained('teachers')->nullOnDelete();

            // Auxiliares
            $table->json('subjects')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
