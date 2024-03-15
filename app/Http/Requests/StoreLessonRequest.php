<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

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
