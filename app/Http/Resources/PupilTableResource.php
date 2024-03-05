<?php

namespace App\Http\Resources;

use App\Enums\LessonStatus;
use App\Models\Pupil;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PupilTableResource extends JsonResource
{
    public $collects = Pupil::class;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'next_lesson' => $this->next_lesson,
            'lesson_count' => $this->lessons->count(),
            'lesson_finished_count' => $this->lessons->where('status', LessonStatus::FINISHED)->count(),
            'profile_photo_path' => $this->user->profile_photo_path,
            'email' => $this->user->email,
        ];
    }
}
