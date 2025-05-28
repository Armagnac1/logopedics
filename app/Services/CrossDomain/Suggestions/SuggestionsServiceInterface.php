<?php

namespace App\Services\CrossDomain\Suggestions;

use App\Models\Lesson;

interface SuggestionsServiceInterface
{
    public function getLearningMaterialSuggestions(Lesson $lesson): array;
}
