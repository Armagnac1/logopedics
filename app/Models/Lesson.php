<?php

namespace App\Models;

use App\Enums\LessonStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;

class Lesson extends Model
{
    use HasFactory;
    use Searchable;

    protected $casts = [
        'status' => LessonStatus::class,
    ];

    protected $guarded = ['id', 'created_at', 'status'];

    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'tutor_comments' => $this->tutor_comments,
            'homework_comments' => $this->homework_comments,
        ];
    }

    public function pupil(): BelongsTo
    {
        return $this->belongsTo(Pupil::class);
    }

    public function learningMaterials(): BelongsToMany
    {
        return $this->belongsToMany(LearningMaterial::class, 'lesson_learning_materials', 'lesson_id', 'learning_material_id')->withPivot('id');
    }
}
