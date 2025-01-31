<?php

namespace App\Services;

use App\Models\Lesson;

class AISuggestionsService
{
    protected $AIAssistantService;

    public function __construct(GroqApiService $AIAssistantService)
    {
        $this->AIAssistantService = $AIAssistantService;
    }

    public function getSuggestions(array $inputData)
    {
        $prompt = $this->preparePrompt($inputData);
        $response = $this->AIAssistantService->sendRequest(['prompt' => $prompt]);
        return $this->processResponse($response);
    }

    public function getLearningMaterialSuggestions(Lesson $lesson)
    {
        $learningMaterials = $this->getPupilsLearningMaterials($lesson);
        $otherLessonsLearningMaterials = $this->getGroupedMaterialsJson($lesson);
        $prompt = $this->prepareLearningMaterialsPrompt($learningMaterials, $otherLessonsLearningMaterials);
        $response = $this->getSuggestions(['prompt' => $prompt]);
        return $this->extractLearningMaterialIds($response);
    }

    public function getGroupedMaterialsJson(Lesson $lesson)
    {
        $currentPupilId = $lesson->pupil->id;

        $groupedMaterials = Lesson::with(['pupil', 'learningMaterials'])
            ->get()
            ->filter(function ($lesson) use ($currentPupilId) {
                return $lesson->pupil !== null
                    && $lesson->learningMaterials->isNotEmpty()
                    && $lesson->pupil->id !== $currentPupilId;
            })
            ->groupBy(function ($lesson) {
                return $lesson->pupil->id;
            })
            ->map(function ($lessons) {
                return $lessons->map(function ($lesson) {
                    return $lesson->learningMaterials->pluck('id')->toArray();
                });
            })->values();

        // Convert to JSON
        return $groupedMaterials->toJson();
    }



    public function getPupilsLearningMaterials(Lesson $lesson)
    {
        return Lesson::where('pupil_id', $lesson->pupil->id)
            ->with(['learningMaterials'])
            ->get()
            ->map(function (Lesson $lessonItem) use ($lesson) {
                $materials = $lessonItem->learningMaterials->pluck('id')->toArray();
                if ($lessonItem->id === $lesson->id) {
                    // Append a single asterisk after all materials for the current lesson
                    $materials[] = '*';
                }
                return $materials;
            })
            ->values()
            ->toJson();
    }



    private function preparePrompt(array $inputData)
    {
        return "Based on the following data, provide suggestions: " . json_encode($inputData);
    }

    private function prepareLearningMaterialsPrompt(string $learningMaterials, string $otherLessonsLearningMaterials)
    {
        $prompt = <<<TEXT
        Given a JSON array where each item is a pupil. Each pupil has an array of lessons (ordered by date). Each lesson is an array of learningMaterial IDs:

        {$otherLessonsLearningMaterials}

        Another pupil has this lesson history. learningMaterials to suggest marked with "*":

        {$learningMaterials}

        Generate a new lesson (array of 10 unique learningMaterial IDs) by analyzing lessons from other pupils at the same index as the marked one.
        Do not reuse materials from any of the target pupil’s previous lessons.

        Prioritize:
        1. Pupils with similar lesson history
        2. Learning materials from that same lesson index

        Output: JSON array of 10 unique learningMaterial IDs. No explanation.
        TEXT;
        return $prompt;
    }

    private function processResponse($response)
    {
        if (isset($response['choices'][0]['message']['content'])) {
            return $response['choices'][0]['message']['content'];
        }

        return 'No suggestions available.';
    }

    private function extractLearningMaterialIds($responseContent)
    {
        $ids = [];
        if (is_string($responseContent)) {
            // Remove square brackets from the response content
            $responseContent = str_replace(['[', ']'], '', $responseContent);
            $ids = array_map('trim', explode(',', $responseContent));
        }
        return $ids;
    }

}
