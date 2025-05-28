<?php

namespace App\Services\CrossDomain\Suggestions\Ai;

interface AiProviderInterface
{
    public function ask(string $prompt): string;
}
