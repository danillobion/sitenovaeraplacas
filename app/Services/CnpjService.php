<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CnpjService
{
    public static function consultar($cnpj)
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);

        if (strlen($cnpj) !== 14) {
            return ['error' => 'CNPJ inválido'];
        }

        $response = Http::get("https://publica.cnpj.ws/cnpj/{$cnpj}");

        if ($response->failed()) {
            return ['error' => 'Erro ao consultar o CNPJ'];
        }

        $dados = $response->json();

        if (isset($dados['erro'])) {
            return ['error' => 'CNPJ não encontrado'];
        }

        return [
            'nome' => $dados['razao_social'],
            'atualizado_em' => $dados['atualizado_em'],
            'telefone1' => $dados['estabelecimento']['ddd1'] . $dados['estabelecimento']['telefone1'],
            'cep' => $dados['estabelecimento']['cep'],
            'logradouro' => $dados['estabelecimento']['logradouro'],
            'bairro' => $dados['estabelecimento']['bairro'],
            'cidade' => $dados['estabelecimento']['cidade']['nome'],
            'uf' => $dados['estabelecimento']['estado']['sigla'],
            'numero' => $dados['estabelecimento']['numero'],
            'complemento' => $dados['estabelecimento']['complemento']
        ];
    }
}
