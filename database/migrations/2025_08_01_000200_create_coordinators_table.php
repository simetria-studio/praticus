<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coordinators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('cpf')->unique()->nullable();
            $table->string('registration')->unique(); // Matrícula funcional
            $table->string('specialty')->nullable(); // Especialidade/área
            $table->string('degree')->nullable(); // Formação acadêmica
            $table->string('institution')->nullable(); // Instituição de formação
            $table->year('graduation_year')->nullable();
            $table->enum('status', ['ativo', 'inativo'])->default('ativo');

            // Relacionamentos
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Para login no sistema

            // Dados de coordenação
            $table->json('coordinated_grades')->nullable(); // Série/anos que coordena
            $table->json('coordinated_subjects')->nullable(); // Disciplinas que coordena
            $table->date('hiring_date')->nullable(); // Data de contratação como coordenador
            $table->string('contract_type')->nullable(); // Tipo de contrato
            $table->decimal('salary', 10, 2)->nullable(); // Salário
            $table->string('workload')->nullable(); // Carga horária

            // Endereço
            $table->string('street')->nullable();
            $table->string('number')->nullable();
            $table->string('complement')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city')->nullable();
            $table->string('state', 2)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('country')->default('Brasil');

            // Informações adicionais
            $table->text('observations')->nullable();
            $table->string('photo')->nullable(); // Caminho para foto

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coordinators');
    }
};
