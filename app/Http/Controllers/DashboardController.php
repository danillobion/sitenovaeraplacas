<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return Inertia::render('Dashboard',[
            "quantidade_requisicoes_total" => 0,
            "quantidade_requisicoes_cpf" => 0,
            "quantidade_requisicoes_cnpj" => 0,
        ]);
    }
}
