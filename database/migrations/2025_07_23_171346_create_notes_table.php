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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();

            // Relacionamentos
            $table->foreignId('student_id')->constrained()->onDelete('cascade');

            // Dados da nota
            $table->string('subject'); // Disciplina (Matemática, Português, etc.)
            $table->string('period'); // Período (1º Bimestre, 2º Bimestre, etc.)
            $table->decimal('grade', 4, 2); // Nota obtida (0.00 a 10.00)
            $table->decimal('max_grade', 4, 2)->default(10.00); // Nota máxima possível
            $table->string('evaluation_type')->default('prova'); // Tipo: prova, trabalho, participação, etc.

            // Dados complementares
            $table->date('evaluation_date'); // Data da avaliação
            $table->integer('school_year'); // Ano letivo
            $table->string('class')->nullable(); // Turma específica
            $table->decimal('weight', 3, 2)->default(1.00); // Peso da nota (para médias ponderadas)

            // Observações e status
            $table->text('observations')->nullable(); // Observações do professor
            $table->enum('status', ['ativa', 'cancelada', 'corrigida'])->default('ativa');

            // Metadados
            $table->string('created_by')->nullable(); // Quem lançou a nota
            $table->string('updated_by')->nullable(); // Quem atualizou

            $table->timestamps();

            // Índices para performance
            $table->index(['student_id', 'subject', 'period']);
            $table->index(['school_year', 'subject']);
            $table->index('evaluation_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
