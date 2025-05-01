<?php

namespace App\Services\Ai;

use App\Services\Ai\Providers\GroqProvider;
use App\Services\Ai\Providers\DeepSeekProvider;
use App\Services\Ai\Providers\OpenRouterProvider;

class AiProviderFactory
{
    public function make(): AiProviderInterface
    {
        $drivers = config('services.ai.drivers');
        $useChain = config('services.ai.chain');

        $providers = array_map(function ($driver) {
            return match (trim($driver)) {
                'groq' => new GroqProvider(),
                'deepseek' => new DeepSeekProvider(),
                'openrouter' => new OpenRouterProvider(),
                default => throw new \InvalidArgumentException("Unsupported provider: $driver"),
            };
        }, $drivers);

        return $useChain
            ? new AiProviderChain($providers)
            : $providers[0];
    }
}
