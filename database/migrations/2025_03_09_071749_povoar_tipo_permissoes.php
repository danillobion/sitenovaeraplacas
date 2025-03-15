<?php

use App\Models\Permissao;
use App\Models\Usuario;
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

        $permissao_dashboard_admin = Permissao::where('nome', 'dashboard-admin')->first();
        $permissao_tela_listagem_estampadoras = Permissao::where('nome', 'tela-listagem-estampadoras')->first();

        DB::table('tipo_permissoes')->insert([
            ['tipo_id' => $tipo_root->tipo->id, 'permissao_id' => $permissao_dashboard_admin->id, 'created_at' => now(), 'updated_at' => now()],
            ['tipo_id' => $tipo_root->tipo->id, 'permissao_id' => $permissao_tela_listagem_estampadoras->id, 'created_at' => now(), 'updated_at' => now()],
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
