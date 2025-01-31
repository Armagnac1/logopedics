<?php

namespace App\Models;

use App\Enums\LessonStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;
use Spatie\Tags\HasTags;

class Pupil extends Model
{
    use HasFactory;
    use Searchable;
    use HasTags;
    protected $guarded = ['id', 'created_at'];
    public function toSearchableArray(): array
    {
        return [
            'full_name' => $this->full_name
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

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
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
