<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\LogConsulta;
use Illuminate\Http\Request;
use App\Services\EncomendaService;

class EncomendaController extends Controller
{
    public function index(){
        return Inertia::render('Admin/Consultar/Encomenda/index', [
            'apiDisponivel' => EncomendaService::configurado(),
            'quantidadeMinima' => 10,
        ]);
    }

    public function consultar(Request $request)
    {
        $request->merge([
            'placa' => $request->filled('placa')
                ? strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $request->input('placa')))
                : null,
            'cnpj_estampadora' => $request->filled('cnpj_estampadora')
                ? preg_replace('/\D/', '', (string) $request->input('cnpj_estampadora'))
                : null,
        ]);

        $dados = $request->validate([
            'placa' => 'nullable|string|size:7',
            'cnpj_estampadora' => 'nullable|string|size:14',
            'situacao' => 'nullable|in:ABE,FIN,CAN',
            'situacao_pagamento' => 'nullable|in:PAG,EMT,PEN,CAN',
            'numero_autorizacao' => 'nullable|string|max:15',
            'numero_autorizacao_local' => 'nullable|string',
            'id' => 'nullable|integer',
            'data_inicio_abertura' => 'nullable|date',
            'data_fim_abertura' => 'nullable|date',
            'quantidade' => 'nullable|integer|min:10|max:100',
        ]);

        $dados['quantidade'] = max((int) ($dados['quantidade'] ?? 10), 10);

        $dadosFiltrados = array_filter($dados, fn ($valor) => !is_null($valor) && $valor !== '');

        $resultado = EncomendaService::consultar($dadosFiltrados);

        $this->registrarLog($dadosFiltrados, $resultado);

        return response()->json($resultado);
    }

    private function registrarLog($entrada, $saida)
    {
        $log = new LogConsulta();
        $log->usuario_id = auth()->user()->id;
        $log->tipo = "ENC";
        $log->json_envio = json_encode($entrada);
        $log->json_retorno = json_encode($saida);
        $log->save();
    }

}
