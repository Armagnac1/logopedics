<?php

namespace App\Models;

use App\Enums\LessonStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

class Pupil extends Model
{
    use HasFactory;
    use Searchable;

    protected $appends = ['full_name'];

    #[SearchUsingPrefix(['first_name', 'last_name'])]
    public function toSearchableArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
        ];
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getNextLessonAttribute()
    {
        return $this->lessons->sortBy('start_at')
            ->where('status', '=', LessonStatus::SCHEDULED)
            ->whereNotNull('start_at')
            ->first();
    }

    public function getLessonCountAttribute()
    {
        return $this->lessons->count();
    }
}
