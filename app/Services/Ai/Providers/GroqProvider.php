<?php

namespace App\Services\Ai\Providers;

use Illuminate\Support\Facades\Http;

class GroqProvider extends AbstractAiProvider
{
    public function ask(string $prompt): string
    {
        try {
            $response = Http::withToken(config('services.ai.keys.groq'))
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.3-70b-versatile',
                    'temperature' => 0.2,
                    'messages' => [['role' => 'user', 'content' => $prompt]],
                ]);

            if (! $response->successful()) {
                $this->handleError('Groq', $response);
            }

            $data = $response->json();

            return $data['choices'][0]['message']['content'] ?? 'No response from AI provider';
        } catch (\Throwable $e) {
            $this->handleException('GroqProvider', $e);
        }
    }
}
