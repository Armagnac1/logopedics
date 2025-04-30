<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Tags\Tag as SpatieTag;
use App\Models\Tag as AppTag;
use Illuminate\Support\Facades\Log;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedLogopedicsGroups();
        $this->seedSourceTags();
    }

    private function seedLogopedicsGroups(): void
    {
        $logopedicsGroups = [
            'Артикуляционная гимнастика',
            'Дыхательная гимнастика',
            'Работа над звуком',
            'Физминутка',
            'Игры из Мерсибо',
            'ВПФ'
        ];

        collect($logopedicsGroups)->each(function ($group) {
            try {
                SpatieTag::findOrCreateFromString($group, 'learning_material');
            } catch (\Exception $e) {
                Log::error("Failed to create or find tag: {$group}", ['error' => $e->getMessage()]);
            }
        });
    }

    private function seedSourceTags(): void
    {
        $sourceTags = [
            'Частный',
            'Белая Цапля',
            'Школа'
        ];

        collect($sourceTags)->each(function ($tag) {
            try {
                AppTag::create([
                    'name' => ['ru' => $tag],
                    'model' => 'App\Models\Pupil',
                    'type' => 'Источник',
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to create tag: {$tag}", ['error' => $e->getMessage()]);
            }
        });
    }
}
