<?php

namespace App\Http\Controllers;

use App\Services\CepService;
use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    public function cep($numero = null){
        $cepService = CepService::consultar($numero);
        return $cepService;
    }
}
