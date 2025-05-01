<?php

namespace App\Services\Ai\Providers;

use Illuminate\Support\Facades\Http;

class DeepSeekProvider extends AbstractAiProvider
{
    public function ask(string $prompt): string
    {
        try {
            $response = Http::withToken(config('services.ai.keys.deepseek'))
                ->post('https://api.deepseek.com/v1/chat/completions', [
                    'model' => 'deepseek-chat',
                    'messages' => [['role' => 'user', 'content' => $prompt]],
                ]);

            if (! $response->successful()) {
                $this->handleError('DeepSeek', $response);
            }

            $data = $response->json();

            return $data['choices'][0]['message']['content'] ?? 'No response from AI provider';
        } catch (\Throwable $e) {
            $this->handleException('DeepSeekProvider', $e);
        }
    }
}
