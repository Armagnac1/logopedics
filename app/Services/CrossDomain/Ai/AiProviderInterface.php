<?php

namespace App\Services\CrossDomain\Ai;

interface AiProviderInterface
{
    public function ask(string $prompt): string;
}
