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
            ['id' => 19, 'nome' => 'tela-tipos-usuarios', 'descricao' => 'Tela para acessar os usuários', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 20, 'nome' => 'tela-listagem-usuarios', 'descricao' => 'Tela para acessar os tipos de usuários', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
