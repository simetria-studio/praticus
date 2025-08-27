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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();

            // Dados da escola
            $table->string('name');
            $table->string('code')->unique()->nullable(); // Código da escola
            $table->string('cnpj')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['publica', 'privada', 'federal', 'estadual', 'municipal'])->default('privada');
            $table->enum('level', ['infantil', 'fundamental', 'medio', 'superior', 'tecnico', 'todos'])->default('todos');
            $table->boolean('active')->default(true);

            // Endereço
            $table->string('street'); // Rua
            $table->string('number'); // Número
            $table->string('complement')->nullable(); // Complemento
            $table->string('neighborhood'); // Bairro
            $table->string('city'); // Cidade
            $table->string('state', 2); // Estado (sigla)
            $table->string('postal_code', 10); // CEP
            $table->string('country')->default('Brasil');

            // Coordenadas geográficas (opcional)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
