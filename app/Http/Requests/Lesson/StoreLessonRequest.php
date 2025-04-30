<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pupil_id' => ['required', 'integer', 'exists:pupils,id'],
            'title' => ['nullable', 'string'],
            'start_time' => ['required', 'date_format:H:i'],
            'start_dates' => ['required', 'array'],
            'start_dates.*' => ['required', 'date'],
            'duration' => ['integer', 'min:5', 'max:240'],
        ];
    }
}
