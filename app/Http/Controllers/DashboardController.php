<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\LogConsulta;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isRoot()) {
            $quantidades = LogConsulta::selectRaw("
                count(*) as quantidade_consultas,
                sum(case when tipo = 'CEP' then 1 else 0 end) as quantidade_consultas_cep,
                sum(case when tipo = 'CNP' then 1 else 0 end) as quantidade_consultas_cnpj
            ")
            ->first();
        } else {
            $quantidades = LogConsulta::where("usuario_id", $user->id)
            ->selectRaw("
                count(*) as quantidade_consultas,
                sum(case when tipo = 'CEP' then 1 else 0 end) as quantidade_consultas_cep,
                sum(case when tipo = 'CNP' then 1 else 0 end) as quantidade_consultas_cnpj
            ")
            ->first();
        }

        return Inertia::render('Dashboard', [
            "quantidade_consultas" => $quantidades->quantidade_consultas,
            "quantidade_consultas_cep" => $quantidades->quantidade_consultas_cep,
            "quantidade_consultas_cnpj" => $quantidades->quantidade_consultas_cnpj,
        ]);
    }
}
