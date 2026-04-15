<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EncomendaService
{
    private static $endpoint = 'https://www.emplaca.ai/api/v1/encomendas';

    public static function configurado(): bool
    {
        return filled(trim((string) env('ENCOMENDA_API_TOKEN')));
    }

    public static function consultar(array $filtros = [])
    {
        $token = trim((string) env('ENCOMENDA_API_TOKEN'));

        if ($token === '') {
            return [
                'error' => true,
                'message' => 'A API de encomendas nao esta configurada neste ambiente.',
                'status' => 500,
            ];
        }

        $response = Http::acceptJson()
            ->timeout(20)
            ->withToken($token)
            ->get(self::$endpoint, $filtros);

        if ($response->failed()) {
            $errorData = $response->json();

            $message = data_get($errorData, 'errors.default')
                ?? data_get($errorData, 'message')
                ?? 'Nao foi possivel consultar as encomendas no momento.';

            return [
                'error' => true,
                'message' => $message,
                'status' => $response->status(),
            ];
        }

        return $response->json();
    }
}
