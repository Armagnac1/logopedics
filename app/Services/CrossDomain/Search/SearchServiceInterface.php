<?php

namespace App\Services\CrossDomain\Search;

interface SearchServiceInterface
{
    public function getForIndex(?string $searchInput = null): array;
}
