<?php

namespace App\Http\Controllers;

use App\Enums\LessonStatus;
use App\Http\Requests\StorePupilRequest;
use App\Http\Requests\UpdatePupilRequest;
use App\Http\Resources\PupilTableResource;
use App\Models\Pupil;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
            ->joinSub($upcomingLessons, 'lessons', function ($join) {
                $join->on('pupils.id', '=', 'lessons.pupil_id');
            })
            ->where('tutor_id', $tutorId)
            ->with(['lessons', 'user'])
            ->orderBy('lessons.start_at', 'ASC')
            ->paginate(20)->withQueryString();
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
        return Inertia::render('Pupil/CreateShow');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePupilRequest $request)
    {
        $pupil = new Pupil($request->validated());
        $pupil->tutor_id = auth()->user()->tutor->id;
        $pupil->save();
        $request->session()->flash('flash.banner', 'Новый ученик создан');
        $request->session()->flash('flash.bannerStyle', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pupil $pupil)
    {
        return Inertia::render('Pupil/CreateShow', [
            'pupil' => $pupil->load(['lessons.learningMaterials.tags'])
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pupil $pupil)
    {
        //
    }
}
