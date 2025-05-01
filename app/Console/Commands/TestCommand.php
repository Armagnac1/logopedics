<?php

namespace App\Console\Commands;

use App\Models\LearningMaterial;
use App\Models\Lesson;
use App\Services\Ai\AISuggestionsService;
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

    public function __construct(private AISuggestionsService $aiService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lessonId = $this->argument('lessonId');
        $lesson = Lesson::with('pupil', 'learningMaterials')->find($lessonId);

        if (!$lesson) {
            $this->error("Lesson with ID $lessonId not found.");
            return Command::FAILURE;
        }

        $this->info("Generating suggestions for Lesson ID: $lessonId");
        $suggestions = $this->aiService->getLearningMaterialSuggestions($lesson);

        $this->info('Suggested learning material IDs:');
        $this->line(json_encode($suggestions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return Command::SUCCESS;
    }
}
