<?php

namespace App\Services\Abstracts;

interface SearchServiceInterface
{
    public function getForIndex(string $searchInput = null): array;
}
