<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CnpjService;

class CnpjController extends Controller
{
    public function cnpj($numero = null){
        $cnpjService = CnpjService::consultar($numero);
        return $cnpjService;
    }
}
