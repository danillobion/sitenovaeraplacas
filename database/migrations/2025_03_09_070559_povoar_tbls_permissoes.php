<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('permissoes')->insert([
            
            ['nome' => 'tela-listagem-estampadoras', 'descricao' => 'Tela de listagem das estampadoras', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'tela-alterar-estampadoras', 'descricao' => 'Tela de alterar estampadoras', 'created_at' => now(), 'updated_at' => now()],

            ['nome' => 'tela-listagem-produtos', 'descricao' => 'Tela de listagem dos produtos', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'tela-alterar-produtos', 'descricao' => 'Tela de alterar produtos', 'created_at' => now(), 'updated_at' => now()],

            ['nome' => 'tela-consultar-cep', 'descricao' => 'Tela para consultar cep', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'tela-consultar-cnpj', 'descricao' => 'Tela para consultar cnpj', 'created_at' => now(), 'updated_at' => now()],
            
            ['nome' => 'tela-log-consultas', 'descricao' => 'Tela para ver o log das consultas', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
