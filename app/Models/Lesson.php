<?php

namespace App\Models;

use App\Enums\LessonStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lesson extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => LessonStatus::class,
    ];

    protected $guarded = ['id', 'pupil_id', 'created_at', 'status'];

    public function pupil(): BelongsTo
    {
        return $this->belongsTo(Pupil::class);
    }

    public function learningMaterials(): BelongsToMany
    {
        return $this->belongsToMany(LearningMaterial::class, 'lesson_learning_materials', 'lesson_id', 'learning_material_id');
    }

}
