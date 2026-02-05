<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public static function send(string $phone, string $message): bool
    {
        $instance = config('services.zapi.instance');
        $token    = config('services.zapi.token');

        if (!$instance || !$token) {
            Log::error('Z-API não configurada corretamente');
            return false;
        }

        $response = Http::post(
            "https://api.z-api.io/instances/{$instance}/token/{$token}/send-text",
            [
                'phone'   => $phone,
                'message' => $message,
            ]
        );

        Log::info('Z-API response', [
            'status' => $response->status(),
            'body'   => $response->body()
        ]);

        return $response->successful();
    }
}
