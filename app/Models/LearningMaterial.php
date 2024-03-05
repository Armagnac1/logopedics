<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Tags\HasTags;

class LearningMaterial extends Model
{
    use HasFactory;
    use HasTags;

    protected $fillable = ['title', 'text'];


    public function creator_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }
}
