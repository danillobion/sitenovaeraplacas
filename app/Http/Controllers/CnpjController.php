<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\LogConsulta;
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

        $this->registrarLog($numero, $resultado);
        
        return $resultado;
    }

    public function cnpj($numero = null){
        $cnpjService = CnpjService::consultar($numero);
        return $cnpjService;
    }

    private function registrarLog($numero, $resultado){
        $log = new LogConsulta();
        $log->usuario_id = auth()->user()->id;
        $log->tipo = "CNP";
        $log->json_envio = json_encode(["cnpj" => $numero]);
        $log->json_retorno = json_encode($resultado);
        $log->save();
    }
}
