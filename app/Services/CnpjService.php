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
            'complemento' => $dados['estabelecimento']['complemento'],

            'socios' => $dados['socios'],

            'atualizado_em' => $dados['atualizado_em'],

            'razao_social' => $dados['razao_social'],

            'cnpj_raiz' => $dados['cnpj_raiz'],

            'responsavel_federativo' => $dados['responsavel_federativo'],

            'porte' => $dados['porte'],

            'natureza_juridica' => $dados['natureza_juridica'],

            'qualificacao_do_responsavel' => $dados['qualificacao_do_responsavel'],

            'simples' => $dados['simples'],

            'atividades_secundarias' => $dados['estabelecimento']['atividades_secundarias'],
        ];
    }
}
