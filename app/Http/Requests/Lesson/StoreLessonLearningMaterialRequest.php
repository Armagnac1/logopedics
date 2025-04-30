<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonLearningMaterialRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'lessonId' => ['integer', 'exists:lessons,id'],
            'materials' => ['array'],
            'materials.id' => ['integer', 'exists:learning_materials,id'],
        ];
    }
}
