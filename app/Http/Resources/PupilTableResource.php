<?php

namespace App\Http\Resources;

use App\Models\Pupil;
use Carbon\Carbon;
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
            'next_lesson' => $this->lessons->filter(function ($lesson) {
                $date = $lesson->start_at;
                if (!$date) {
                    return false;
                }
                return Carbon::parse($date)->isFuture();
            })->first(),
            'lesson_count' => $this->lessons->count(),
            'lesson_finished_count' => $this->lessons->filter(function ($lesson) {
                $date = $lesson->start_at;
                if (!$date) {
                    return false;
                }
                return Carbon::parse($date)->isPast();
            })->count(),
            'profile_photo_path' => optional($this->user)->profile_photo_path,
            'email' => optional($this->user)->email
        ];
    }
}
