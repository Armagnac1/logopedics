<?php

namespace Database\Seeders;

use App\Models\LearningMaterial;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LearningMaterialSeeder extends Seeder
{
    protected $materialGroups = ['Этап работы', 'Методический материал', 'Категория звука', 'Звук', 'Единица языка'];

    public function run(): void
    {
        $materials = $this->getMaterialsFromJson();
        $result = $this->processMaterials($materials);
        $this->createTags($result);
        $this->createLearningMaterials($result);
    }

    private function getMaterialsFromJson(): \Illuminate\Support\Collection
    {
        $json = Storage::disk('local')->get('/json/novator.json');

        return collect(json_decode($json, true));
    }

    private function processMaterials(\Illuminate\Support\Collection $materials): \Illuminate\Support\Collection
    {
        return $materials->map(fn ($m) => $this->processMaterial($m))->filter();
    }

    private function processMaterial(array $material): ?\Illuminate\Support\Collection
    {
        if (empty($material['title'])) {
            return null;
        }

        foreach ($this->materialGroups as $group) {
            $material[$group] = $this->separateGroups($material[$group]);
        }

        $material['file_path'] = str_replace('/ЛОГО-презентации /', '', $material['Path'].'/'.$material['Filename']);
        $material['title'] = pathinfo($material['title'], PATHINFO_FILENAME);

        return collect($material);
    }

    private function separateGroups(string $groups): \Illuminate\Support\Collection
    {
        return empty($groups) ? collect() : Str::of($groups)->explode('-')->map(fn ($word) => Str::ucfirst($word));
    }

    private function createTags(\Illuminate\Support\Collection $result): void
    {
        foreach ($this->materialGroups as $group) {
            $result->pluck($group)->flatten()->unique()->each(fn ($tag) => $this->createTag($tag, $group));
        }
    }

    private function createTag(string $tag, string $group): void
    {
        Tag::create([
            'name' => ['ru' => $tag],
            'model' => LearningMaterial::class,
            'type' => $group,
        ]);
    }

    private function createLearningMaterials(\Illuminate\Support\Collection $result): void
    {
        $missingFiles = collect();

        foreach ($result as $materialData) {
            $filePath = 'presentations/'.$materialData['file_path'];

            if (! Storage::disk('public')->exists($filePath)) {
                $missingFiles->push($materialData['file_path']);

                continue;
            }

            $tags = Tag::whereIn('name->ru', $materialData->only($this->materialGroups)->flatten())->get();
            $material = LearningMaterial::create([
                'creator_user_id' => '1',
                'title' => $materialData['title'],
            ]);

            $material->attachTags($tags);
            $material->addMediaFromDisk($filePath, 'public')->toMediaCollection('learning_materials_files');
        }

        dd($missingFiles);
    }
}
