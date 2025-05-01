<?php

namespace App\Services\Ai\Providers;

use Illuminate\Support\Facades\Http;

class OpenRouterProvider extends AbstractAiProvider
{
    public function ask(string $prompt): string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.config('services.ai.keys.openrouter'),
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name'),
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'google/gemini-2.0-flash-exp:free',
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ]);

            if (! $response->successful()) {
                $this->handleError('OpenRouter', $response);
            }

            $data = $response->json();

            return $data['choices'][0]['message']['content'] ?? 'No response from AI provider';
        } catch (\Throwable $e) {
            $this->handleException('OpenRouterProvider', $e);
        }
    }
}
