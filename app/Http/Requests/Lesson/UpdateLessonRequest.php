<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['string', 'nullable'],
            'start_at' => ['date', 'nullable'],
            'duration' => ['integer', 'min:5', 'max:240', 'nullable'],
            'homework_comments' => ['string', 'nullable'],
            'tutor_comments' => ['string', 'nullable'],
        ];
    }
}
