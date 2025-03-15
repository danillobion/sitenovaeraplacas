<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Services\CepService;
use Illuminate\Http\Request;

class CepController extends Controller
{
    public function index(){
        return Inertia::render('Admin/Consultar/Cep/index',[
            'cep' => []
        ]);
    }
    public function consultarCep($numero){
        $resultado = [];
        $resultado = CepService::consultar($numero);

        if(is_null($resultado)){
            return [];
        }
        
        return $resultado;
    }
}
