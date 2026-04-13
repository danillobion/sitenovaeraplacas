<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $now = now();

        $tipoRoot = DB::table('tipos')->where('nome', 'root')->first();

        if (! $tipoRoot) {
            DB::table('tipos')->insert([
                'nome' => 'root',
                'descricao' => 'Administrador do sistema',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $tipoRoot = DB::table('tipos')->where('nome', 'root')->first();
        } else {
            DB::table('tipos')
                ->where('id', $tipoRoot->id)
                ->update([
                    'descricao' => 'Administrador do sistema',
                    'updated_at' => $now,
                ]);
        }

        $usuarioRoot = DB::table('usuarios')->where('email', 'root@email.com')->first();

        if ($usuarioRoot) {
            DB::table('usuarios')
                ->where('id', $usuarioRoot->id)
                ->update([
                    'nome' => 'Root',
                    'password' => Hash::make('TESTE312'),
                    'tipo_id' => $tipoRoot->id,
                    'updated_at' => $now,
                ]);
        } else {
            DB::table('usuarios')->insert([
                'nome' => 'Root',
                'email' => 'root@email.com',
                'password' => Hash::make('TESTE312'),
                'tipo_id' => $tipoRoot->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $permissaoIds = DB::table('permissoes')->pluck('id');

        foreach ($permissaoIds as $permissaoId) {
            $existeVinculo = DB::table('tipo_permissoes')
                ->where('tipo_id', $tipoRoot->id)
                ->where('permissao_id', $permissaoId)
                ->exists();

            if (! $existeVinculo) {
                DB::table('tipo_permissoes')->insert([
                    'tipo_id' => $tipoRoot->id,
                    'permissao_id' => $permissaoId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tipoRootId = DB::table('tipos')->where('nome', 'root')->value('id');

        DB::table('usuarios')->where('email', 'root@email.com')->delete();

        if ($tipoRootId) {
            DB::table('tipo_permissoes')->where('tipo_id', $tipoRootId)->delete();
        }
    }
};
