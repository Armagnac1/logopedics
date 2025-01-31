<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Tags\Tag;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logopedicsGroups = [
            'Артикуляционная гимнастика',
            'Дыхательная гимнастика',
            'Работа над звуком',
            'Физминутка',
            'Игры из Мерсибо',
            'ВПФ'
        ];
        collect($logopedicsGroups)->each(fn($group) => Tag::findOrCreateFromString($group, 'learning_material'));
        collect([
            'Частный',
            'Белая Цапля',
            'Школа'
        ])->each(function ($tag) {
            \App\Models\Tag::create([
                'name' => ['ru' => $tag],
                'model' => 'App\Models\Pupil',
                'type' => 'Источник',
            ]);
        });
    }
}
