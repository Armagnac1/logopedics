<?php

namespace App\Services\Ai\Providers;

use App\Exceptions\AiProviderException;
use App\Services\Ai\AiProviderInterface;
use Illuminate\Support\Facades\Log;

abstract class AbstractAiProvider implements AiProviderInterface
{
    abstract public function ask(string $prompt): string;

    protected function handleError(string $provider, $response): void
    {
        Log::error("{$provider} API error", [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
        throw new AiProviderException("AI provider error: {$provider} returned status ".$response->status());
    }

    protected function handleException(string $provider, \Throwable $e): void
    {
        Log::error("{$provider} exception", [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        throw new AiProviderException('AI provider exception: '.$e->getMessage(), 0, $e);
    }
}
