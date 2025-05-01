<?php

namespace App\Services\Ai;

use App\Models\Lesson;

class AISuggestionsService
{
    public function __construct(private AiProviderInterface $provider) {}

    public function getSuggestions(array $inputData): string
    {
        $prompt = $this->buildSimplePrompt($inputData);
        $response = $this->provider->ask($prompt);
        return $response;
    }

    public function getLearningMaterialSuggestions(Lesson $lesson): array
    {
        $ownHistory = $this->getCurrentPupilMaterialHistory($lesson);
        $groupedOthers = $this->getGroupedMaterialHistory($lesson);

        $prompt = $this->buildLearningMaterialPrompt($ownHistory, $groupedOthers);
        $response = $this->provider->ask($prompt);

        return $this->parseMaterialIds($response);
    }

    private function getCurrentPupilMaterialHistory(Lesson $lesson): string
    {
        $pupilId = $lesson->pupil_id;

        $lessons = Lesson::where('pupil_id', $pupilId)
            ->with('learningMaterials')
            ->get()
            ->map(function (Lesson $l) use ($lesson) {
                $ids = $l->learningMaterials->pluck('id')->toArray();
                if ($l->id === $lesson->id) {
                    $ids[] = '*';
                }
                return $ids;
            });

        return $lessons->values()->toJson(JSON_UNESCAPED_UNICODE);
    }

    private function getGroupedMaterialHistory(Lesson $lesson): string
    {
        $currentPupilId = $lesson->pupil_id;

        $grouped = Lesson::with(['pupil', 'learningMaterials'])
            ->get()
            ->filter(fn($l) =>
                $l->pupil && $l->learningMaterials->isNotEmpty() && $l->pupil->id !== $currentPupilId
            )
            ->groupBy(fn($l) => $l->pupil->id)
            ->map(fn($lessons) =>
            $lessons->map(fn($l) =>
            $l->learningMaterials->pluck('id')->toArray()
            )
            );

        return $grouped->values()->toJson(JSON_UNESCAPED_UNICODE);
    }

    private function buildSimplePrompt(array $data): string
    {
        return "Based on the following data, provide suggestions: " .
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
