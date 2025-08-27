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
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // Dados pessoais
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('cpf')->unique()->nullable();
            $table->string('rg')->nullable();
            $table->date('birth_date');
            $table->enum('gender', ['masculino', 'feminino', 'outro'])->nullable();
            $table->string('photo')->nullable(); // Caminho para foto

            // Dados acadêmicos
            $table->string('enrollment')->unique(); // Matrícula
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('grade'); // Série/Ano (1º ano, 2º ano, etc.)
            $table->string('class')->nullable(); // Turma (A, B, C, etc.)
            $table->year('school_year'); // Ano letivo
            $table->enum('status', ['ativo', 'inativo', 'transferido', 'formado'])->default('ativo');

            // Endereço
            $table->string('street'); // Rua
            $table->string('number'); // Número
            $table->string('complement')->nullable(); // Complemento
            $table->string('neighborhood'); // Bairro
            $table->string('city'); // Cidade
            $table->string('state', 2); // Estado (sigla)
            $table->string('postal_code', 10); // CEP
            $table->string('country')->default('Brasil');

            // Dados do responsável
            $table->string('guardian_name'); // Nome do responsável
            $table->string('guardian_phone'); // Telefone do responsável
            $table->string('guardian_email')->nullable(); // Email do responsável
            $table->string('guardian_cpf')->nullable(); // CPF do responsável
            $table->enum('guardian_relationship', ['pai', 'mae', 'avo', 'ava', 'tio', 'tia', 'responsavel_legal', 'outro'])->default('responsavel_legal');

            // Informações adicionais
            $table->text('medical_info')->nullable(); // Informações médicas
            $table->text('observations')->nullable(); // Observações gerais
            $table->date('enrollment_date')->nullable(); // Data da matrícula

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
