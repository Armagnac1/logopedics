<?php

namespace App\Console\Commands;

use App\Models\LearningMaterial;
use App\Models\Lesson;
use App\Services\AISuggestionsService;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-command {lessonId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * The AI Suggestions Service instance.
     *
     * @var AISuggestionsService
     */
    protected $aiSuggestionsService;

    /**
     * Create a new command instance.
     *
     * @param AISuggestionsService $aiSuggestionsService
     */
    public function __construct(AISuggestionsService $aiSuggestionsService)
    {
        parent::__construct();
        $this->aiSuggestionsService = $aiSuggestionsService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lessonId = $this->argument('lessonId');
        $randomLesson = Lesson::whereId($lessonId)->first();
        $resultIds = $this->aiSuggestionsService->getLearningMaterialSuggestions($randomLesson);
        $result = LearningMaterial::whereIn('id', $resultIds)->get();

        foreach ($result as $learningMaterial) {
            $this->info('Learning Material ID: ' . $learningMaterial->id);
            $this->info('Title: ' . $learningMaterial->title);
            $this->info('Description: ' . $learningMaterial->description);
            $this->info('---');
        }
    }
}
