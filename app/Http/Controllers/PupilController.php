<?php

namespace App\Http\Controllers;

use App\Enums\LessonStatus;
use App\Http\Requests\StorePupilRequest;
use App\Http\Requests\UpdatePupilRequest;
use App\Http\Resources\PupilTableResource;
use App\Models\Lesson;
use App\Models\Pupil;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Tags\Tag;

class PupilController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Pupil::class, 'pupil');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tutorId = auth()->user()->tutor->id;
        $upcomingLessons = \DB::table('lessons')
            ->selectRaw('pupil_id, MIN(start_at) as start_at')
            ->where('status', LessonStatus::SCHEDULED)
            ->where('lessons.start_at', '>', now())
            ->groupByRaw(1);
        $pupilsOrdered = Pupil::when($request->search, function ($query) use ($request) {
            $pupilsIds = Pupil::search($request->search)->keys();
            $query->whereIn('pupils.id', $pupilsIds);
        })
            ->leftJoinSub($upcomingLessons, 'lessons', function ($join) {
                $join->on('pupils.id', '=', 'lessons.pupil_id');
            })
            ->where('tutor_id', $tutorId)
            ->with(['lessons', 'user'])
            ->orderBy('lessons.start_at', 'ASC')
            ->paginate(30)->withQueryString();
        return Inertia::render('Pupil/Index', [
            'pupils' => PupilTableResource::collection($pupilsOrdered),
            'filters' => $request->only(['search'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Pupil/CreateShow',[
            'tags' => Tag::whereModel(Pupil::class)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePupilRequest $request)
    {
        $pupil = new Pupil($request->validated());
        $pupil->tutor_id = auth()->user()->tutor->id;
        //$pupil->
        $pupil->save();

        $tags = collect($request->tags)->pluck('id')->toArray();
        $pupil->tags()->sync($tags);
        session()->flash('flash.banner', __('messages.model_created', ['model' => __('models.pupil')]));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('pupil.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pupil $pupil)
    {
        $pupil->load(['tags', 'lessons.learningMaterials.tags', 'city']);
        $pupil->setRelation('lessons', $pupil->lessons->sortBy([
                fn(Lesson $a, Lesson $b) => strtotime($a['start_at']) <=> strtotime($b['start_at']),
                fn(Lesson $a, Lesson $b) => $b['id'] <=> $a['id'],
            ])->values());
        return Inertia::render('Pupil/CreateShow', [
            'pupil' => $pupil,
            'tags' => Tag::whereModel(Pupil::class)->get(['id', 'name'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pupil $pupil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePupilRequest $request, Pupil $pupil)
    {
        $pupil->update($request->validated());
        $tags = collect($request->tags)->pluck('id')->toArray();
        $pupil->tags()->sync($tags);
        session()->flash('flash.banner', __('messages.model_updated', ['model' => __('models.pupil')]));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('pupil.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Pupil $pupil)
    {
        $pupil->delete();
        session()->flash('flash.banner', __('messages.model_deleted', ['model' => __('models.pupil')]));
        session()->flash('flash.bannerStyle', 'success');
        return to_route('home');
    }
}
