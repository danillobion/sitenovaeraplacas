<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Services\EncomendaService;

class EncomendaService
{
    private static $endpoint = 'https://www.emplaca.ai/api/v1/encomendas';

    public static function consultar(array $filtros = [])
    {
        $token = env('ENCOMENDA_API_TOKEN');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get(self::$endpoint, $filtros);

        if ($response->failed()) {
            $errorData = $response->json();
            $message = $errorData['errors']['default'] ?? $message;
            return [
                'error' => true,
                'message' => $message,
                'status' => $response->status(),
            ];
        }

        return $response->json();
    }
}
