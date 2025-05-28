<?php

namespace App\Services\CrossDomain\Suggestions\Ai\Providers;

use Illuminate\Support\Facades\Http;

class OpenRouterProvider extends AbstractAiProvider
{
    public function ask(string $prompt): string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.ai.keys.openrouter'),
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name'),
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'google/gemini-2.0-flash-exp:free',
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'temperature' => 0.00,
            ]);

            if (!$response->successful()) {
                $this->handleError('OpenRouter', $response);
            }

            $data = $response->json();
            if (!isset($data['choices'])) {
                $this->handleError('OpenRouter', $response);
            }

            return $data['choices'][0]['message']['content'];
        } catch (\Throwable $e) {
            $this->handleException('OpenRouterProvider', $e);
        }
    }
}
