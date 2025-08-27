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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();

            // Dados pessoais
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('cpf')->unique()->nullable();
            $table->string('rg')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['masculino', 'feminino', 'outro'])->nullable();
            $table->string('photo')->nullable(); // Caminho para foto

            // Dados profissionais
            $table->string('registration')->unique(); // Matrícula funcional
            $table->string('specialty'); // Especialidade/Área
            $table->string('degree')->nullable(); // Formação acadêmica
            $table->string('institution')->nullable(); // Instituição de formação
            $table->year('graduation_year')->nullable(); // Ano de formação
            $table->enum('status', ['ativo', 'inativo', 'aposentado', 'licenca'])->default('ativo');

            // Endereço
            $table->string('street')->nullable();
            $table->string('number')->nullable();
            $table->string('complement')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city')->nullable();
            $table->string('state', 2)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('country')->default('Brasil');

            // Dados de contrato
            $table->date('hiring_date')->nullable(); // Data de contratação
            $table->string('contract_type')->nullable(); // Tipo de contrato
            $table->decimal('salary', 10, 2)->nullable(); // Salário
            $table->string('workload')->nullable(); // Carga horária

            // Informações adicionais
            $table->text('observations')->nullable();
            $table->json('subjects')->nullable(); // Disciplinas que leciona
            $table->json('schools')->nullable(); // Escolas onde atua

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
