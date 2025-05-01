<?php

namespace App\Services\Ai;

interface AiProviderInterface
{
    public function ask(string $prompt): string;
}
