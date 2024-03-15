<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class LearningMaterial extends Model implements HasMedia
{
    use Searchable;
    use HasFactory;
    use HasTags;
    use InteractsWithMedia;

    protected $fillable = ['title', 'text'];

    #[SearchUsingPrefix(['title'])]
    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'text' => $this->text,
        ];
    }
    public function creator_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'lesson_learning_materials', 'learning_material_id', 'lesson_id');
    }
}
