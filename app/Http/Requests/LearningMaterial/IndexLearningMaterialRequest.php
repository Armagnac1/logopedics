<?php

namespace App\Http\Requests\LearningMaterial;

use Illuminate\Foundation\Http\FormRequest;

class IndexLearningMaterialRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'search' => 'nullable|string|max:255',
            'filters' => 'nullable|array',
            'filters.lessonId' => 'nullable|integer|exists:lessons,id',
            'filters.onlyNotUsed' => 'nullable|in:0,1',
            'filters.tags' => 'nullable|array',
            'filters.tags.*.id' => 'integer|exists:tags,id',
            'ai' => 'nullable|boolean',
        ];
    }
}
