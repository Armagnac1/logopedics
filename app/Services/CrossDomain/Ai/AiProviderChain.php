<?php

namespace App\Services\CrossDomain\Ai;

use Throwable;

class AiProviderChain implements AiProviderInterface
{
    public function __construct(private array $providers)
    {
    }

    public function ask(string $prompt): string
    {
        foreach ($this->providers as $provider) {
            try {
                return $provider->ask($prompt);
            } catch (Throwable $e) {
                report($e);

                continue;
            }
        }

        throw new \RuntimeException('All AI providers failed');
    }
}
