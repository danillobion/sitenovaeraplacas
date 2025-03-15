<?php

use App\Models\Usuario;
use App\Models\Permissao;
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
        $tipo_root = Usuario::with("tipo")->where('email', 'root@email.com')->first();

        $permissao_tipos_usuarios = Permissao::where('nome', 'tela-tipos-usuarios')->first();

        DB::table('tipo_permissoes')->insert([
            ['tipo_id' => $tipo_root->tipo->id, 'permissao_id' => $permissao_tipos_usuarios->id, 'created_at' => now(), 'updated_at' => now()],
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
