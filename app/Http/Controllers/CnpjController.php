<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\CnpjService;

class CnpjController extends Controller
{
    public function index(){
        return Inertia::render('Admin/Consultar/Cnpj/index',[
            'cep' => []
        ]);
    }
    public function consultarCnpj($numero){
        $resultado = [];
        $resultado = CnpjService::consultar($numero);

        if(is_null($resultado)){
            return [];
        }
        
        return $resultado;
    }

    public function cnpj($numero = null){
        $cnpjService = CnpjService::consultar($numero);
        return $cnpjService;
    }
}
