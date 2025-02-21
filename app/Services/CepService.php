<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CepService
{

    public static function consultar($cep)
    {
        $cep = preg_replace('/\D/', '', $cep);

        if (strlen($cep) !== 8) {
            return ['error' => 'CEP inválido'];
        }

        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

        if ($response->failed()) {
            return ['error' => 'Erro ao consultar o CEP'];
        }

        $dados = $response->json();

        if (isset($dados['erro'])) {
            return ['error' => 'CEP não encontrado'];
        }

        return [
            'cep' => $dados['cep'],
            'logradouro' => $dados['logradouro'],
            'bairro' => $dados['bairro'],
            'cidade' => $dados['localidade'],
            'uf' => $dados['uf'],
            'complemento' => $dados['complemento'],
        ];
    }
}
