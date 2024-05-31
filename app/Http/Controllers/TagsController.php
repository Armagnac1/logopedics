<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLearningMaterialRequest;
use App\Http\Requests\UpdateLearningMaterialRequest;
use App\Models\LearningMaterial;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\Tag;

class TagsController extends Controller
{
    public function __construct()
    {
        //$this->authorizeResource(LearningMaterial::class, 'learning_material');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!$request->inertia() && $request->expectsJson()) {
            return response()->json([
                'tags' => Tag::whereModel(LearningMaterial::class)->get(['id', 'name']),
            ]);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Tag $tag)
    {
        $tag->delete();
    }
}
