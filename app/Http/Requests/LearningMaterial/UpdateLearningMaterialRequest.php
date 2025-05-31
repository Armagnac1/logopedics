<?php

namespace App\Http\Requests\LearningMaterial;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Cached\CachedUserRepository;

class UpdateLearningMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $learningMaterial = $this->route('learning_material');

        return app()->make(CachedUserRepository::class)->getTutorId(Auth::id()) === $learningMaterial->creator_user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tags' => ['array'],
            'text' => ['string', 'nullable'],
            'title' => ['string'],
            'mediaIds' => ['array'],
        ];
    }
}
