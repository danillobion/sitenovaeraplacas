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

        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();
            $table->string('cep', 8);
            $table->string('uf');
            $table->string('cidade');
            $table->string('bairro');
            $table->string('logradouro');
            $table->string('numero');
            $table->string('complemento')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });

        Schema::create('estampadoras', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cnpj', 14);
            $table->foreignId('endereco_id')->constrained('enderecos')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('caminho_imagem');
            $table->text('descricao')->nullable();
            $table->decimal('valor', 10, 2);
            $table->timestamps();
        });

        Schema::create('horarios_funcionamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estampadora_id')->constrained('estampadoras')->onDelete('cascade');
            $table->string('dia_semana');
            $table->time('abertura')->nullable();
            $table->time('fechamento')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
        Schema::dropIfExists('estampadoras');
        Schema::dropIfExists('enderecos');
        Schema::dropIfExists('tipos');
    }
};
