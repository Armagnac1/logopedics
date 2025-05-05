<?php

namespace App\Console\Commands;

use App\Services\CrossDomain\Ai\AiTextToModelService;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-command {text}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(private AiTextToModelService $aiTextToModelService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $text = $this->argument('text');
        $this->info("Testing AiTextToModelService with input: \"$text\"");

        $result = $this->aiTextToModelService->convertTextToModel($text);

        $this->line("Result:");
        $this->line(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return Command::SUCCESS;
    }
}
