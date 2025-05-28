<?php

namespace App\Services\CrossDomain\Suggestions\Ai;

use App\Models\Lesson;
use App\Repositories\Eloquent\LessonRepository;
use App\Services\CrossDomain\Suggestions\SuggestionsServiceInterface;

class AISuggestionsService implements SuggestionsServiceInterface
{
    public function __construct(
        private AiProviderInterface $provider,
        private LessonRepository    $lessonRepository
    )
    {
    }

    public function getSuggestions(array $inputData): string
    {
        $prompt = $this->buildSimplePrompt($inputData);
        $response = $this->provider->ask($prompt);

        return $response;
    }

    public function getLearningMaterialSuggestions(Lesson $lesson): array
    {
        $ownHistory = $this->lessonRepository->getPupilMaterialHistory($lesson)->values()->toJson(JSON_UNESCAPED_UNICODE);
        $groupedOthers = $this->lessonRepository->getGroupedMaterialHistory($lesson)->values()->toJson(JSON_UNESCAPED_UNICODE);

        $prompt = $this->buildLearningMaterialPrompt($ownHistory, $groupedOthers);
        $response = $this->provider->ask($prompt);

        return $this->parseMaterialIds($response);
    }

    private function buildSimplePrompt(array $data): string
    {
        return 'Based on the following data, provide suggestions: ' .
            json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    private function buildLearningMaterialPrompt(string $own, string $others): string
    {
        return <<<PROMPT
Given a JSON array where each item is a pupil. Each pupil has an array of lessons (ordered by date). Each lesson is an array of learningMaterial IDs:

{$others}

Another pupil has this lesson history. LearningMaterials to suggest are marked with "*":

{$own}

Generate a new lesson (array of 10 unique learningMaterial IDs) by analyzing lessons from other pupils at the same index as the marked one.
Do not reuse materials from the target pupil’s previous lessons.

Prioritize:
1. Pupils with similar lesson history
2. Learning materials from the same lesson index

Output: JSON array of 10 unique learningMaterial IDs. No explanation.
PROMPT;
    }

    private function parseMaterialIds(string $response): array
    {
        $ids = [];
        if (is_string($response)) {
            // Remove square brackets from the response content
            $response = str_replace(['[', ']'], '', $response);
            $ids = array_map('trim', explode(',', $response));
        }

        return $ids;
    }
}
