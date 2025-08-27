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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();

            // Relacionamentos
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_id')->constrained()->onDelete('cascade');

            // Dados do certificado
            $table->string('certificate_number')->unique(); // Número do certificado
            $table->string('certificate_type')->default('conclusao'); // Tipo: conclusao, historico, etc.
            $table->string('course_level'); // Nível: ensino_medio, fundamental, tecnico, etc.
            $table->string('course_name')->nullable(); // Nome do curso (para técnico/superior)

            // Dados acadêmicos específicos
            $table->year('completion_year'); // Ano de conclusão
            $table->date('completion_date'); // Data de conclusão
            $table->date('issue_date'); // Data de emissão do certificado

            // Dados da escola no momento da emissão
            $table->string('school_name'); // Nome da escola
            $table->string('school_cnpj')->nullable();
            $table->string('school_authorization')->nullable(); // Autorização de funcionamento
            $table->string('school_inep')->nullable(); // Código INEP
            $table->text('school_address'); // Endereço completo da escola

            // Dados do aluno no momento da emissão
            $table->string('student_name'); // Nome completo do aluno
            $table->string('student_cpf')->nullable();
            $table->date('student_birth_date');
            $table->string('student_birth_place'); // Naturalidade
            $table->string('student_nationality')->default('BRASILEIRA');
            $table->string('father_name')->nullable(); // Nome do pai
            $table->string('mother_name'); // Nome da mãe

            // Autoridades que assinam
            $table->string('director_name'); // Nome do diretor
            $table->string('director_title')->default('DIRETOR(A)'); // Cargo do diretor
            $table->string('secretary_name')->nullable(); // Nome do secretário
            $table->string('secretary_title')->default('SECRETÁRIO(A)'); // Cargo do secretário

            // Dados de emissão
            $table->string('issue_city'); // Cidade de emissão
            $table->string('issue_state'); // Estado de emissão

            // Controle
            $table->enum('status', ['emitido', 'cancelado', 'reemitido'])->default('emitido');
            $table->text('observations')->nullable(); // Observações
            $table->string('pdf_path')->nullable(); // Caminho do PDF gerado

            // Dados de autenticidade
            $table->string('verification_code')->unique(); // Código de verificação
            $table->string('hash')->nullable(); // Hash para validação

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
