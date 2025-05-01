<?php

namespace App\Services\CrossDomain\Ai\Providers;

use Illuminate\Support\Facades\Http;

class GroqProvider extends AbstractAiProvider
{
    public function ask(string $prompt): string
    {
        try {
            $response = Http::withToken(config('services.ai.keys.groq'))
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'gemma2-9b-it',
                    'temperature' => 0.00,
                    'messages' => [['role' => 'user', 'content' => $prompt]],
                ]);

            if (! $response->successful()) {
                $this->handleError('Groq', $response);
            }

            $data = $response->json();

            return $data['choices'][0]['message']['content'];
        } catch (\Throwable $e) {
            $this->handleException('GroqProvider', $e);
        }
    }
}
