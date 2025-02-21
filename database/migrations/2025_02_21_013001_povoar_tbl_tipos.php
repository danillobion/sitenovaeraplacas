<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('tipos')->insert([
            ['nome' => 'usuario', 'descricao' => 'Usuário comum', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'root', 'descricao' => 'Administrador do sistema', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('tipos')->whereIn('nome', ['usuario', 'root'])->delete();
    }
};
