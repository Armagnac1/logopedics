<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LearningMaterial;
use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryInterface;
use Illuminate\Http\Request;

class TagController extends Controller
{
    protected $tagRepository;

    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function index(Request $request)
    {
        if (! $request->inertia() && $request->expectsJson()) {
            return response()->json([
                'tags' => $this->tagRepository->getTagsForModel(LearningMaterial::class),
            ]);
        }
    }

    public function destroy(Tag $tag)
    {
        $this->tagRepository->deleteTag($tag);
    }
}
