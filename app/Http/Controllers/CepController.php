<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\LogConsulta;
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

        $this->registrarLog($numero, $resultado);
        
        return $resultado;
    }

    private function registrarLog($numero, $resultado){
        $log = new LogConsulta();
        $log->usuario_id = auth()->user()->id;
        $log->tipo = "CEP";
        $log->json_envio = json_encode(["cep" => $numero]);
        $log->json_retorno = json_encode($resultado);
        $log->save();
    }
}
