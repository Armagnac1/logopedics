<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateLessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $lesson = $this->route('lesson');
        return Auth::user()->tutor->id === $lesson->pupil->tutor->id;
    }

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
