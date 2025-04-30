<?php

namespace App\Http\Requests\Pupil;

use Illuminate\Foundation\Http\FormRequest;

class StorePupilRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'age' => ['nullable', 'string', 'max:255'],
            'parent_name' => ['nullable', 'string', 'max:255'],
            'time_zone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'lesson_duration' => ['integer'],
            'tutor_comments' => ['nullable', 'string'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'tags' => ['array'],
        ];
    }
}
