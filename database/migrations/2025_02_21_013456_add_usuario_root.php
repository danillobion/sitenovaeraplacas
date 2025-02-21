<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('usuarios')->insert([
            'nome' => 'Root',
            'email' => 'root@email.com',
            'password' => Hash::make('12345678'),
            'tipo_id' => DB::table('tipos')->where('nome', 'root')->value('id'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('usuarios')->where('email', 'root@email.com')->delete();
    }
};
