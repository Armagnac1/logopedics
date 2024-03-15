<?php

namespace App\Http\Requests;

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
            'material' => ['array'],
            'material.id' => ['integer', 'exists:learning_materials,id'],
        ];
    }
}
