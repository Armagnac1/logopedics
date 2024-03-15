<?php

namespace Database\Seeders;

use App\Models\LearningMaterial;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class LearningMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    protected $materialGroups = ['Этап работы', 'Методический материал', 'Категория звука', 'Звук', 'Единица языка'];

    public function run(): void
    {
        $json = Storage::disk('local')->get('/json/novator.json');
        $materials = collect(json_decode($json, true));
        $result = $materials->map(function ($m) {
            if ($m['title'] === "") {
                return null;
            }
            $separateGroups = function ($groups) {
                if ($groups === "") {
                    return collect();
                }
                return \Str::of($groups)->explode('-')->map(fn($word) => \Str::ucfirst($word));
            };
            foreach ($this->materialGroups as $group) {
                $m[$group] = $separateGroups($m[$group]);
            }
            $m['file_path'] = str_replace('/ЛОГО-презентации /', '', $m['Path'] . '/' . $m['Filename']);
            $m['title'] = pathinfo($m['title'], PATHINFO_FILENAME);
            return collect($m);
        })->filter();
        $this->createTags($result);
        $missingFiles = collect();
        foreach ($result as $materialData) {
            if (!Storage::disk('public')->exists('presentations/' . $materialData['file_path'])) {
                $missingFiles->push($materialData['file_path']);
                continue;
            }
            $tags = Tag::whereIn('name->ru', $materialData->only($this->materialGroups)->flatten())->get();
            $material = LearningMaterial::create([
                'creator_user_id' => '1',
                'title' => $materialData['title'],
            ]);
            $material->attachTags($tags);
            $pathToFile = 'presentations/' . $materialData['file_path'];
            $material->addMediaFromDisk($pathToFile, 'public')->toMediaCollection('learning_materials_files');
        }
        dd($missingFiles);
    }

    /**
     * @param array $materialGroups
     * @param \Illuminate\Support\Collection $result
     * @return void
     */
    private function createTags(\Illuminate\Support\Collection $result): void
    {
        foreach ($this->materialGroups as $group) {
            $result->map(fn($m) => $m[$group])->flatten()->unique()->values()->each(function ($tag) use ($group) {
                \App\Models\Tag::create([
                    'name' => ['ru' => $tag],
                    'model' => 'App\Models\LearningMaterial',
                    'type' => $group,
                ]);
            });
        }
    }

}
