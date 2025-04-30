<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pupil\StorePupilRequest;
use App\Http\Requests\Pupil\UpdatePupilRequest;
use App\Http\Resources\PupilTableResource;
use App\Models\Pupil;
use App\Repositories\Abstracts\PupilRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Tags\Tag;

class PupilController extends Controller
{
    protected $pupilRepository;

    public function __construct(PupilRepositoryInterface $pupilRepository)
    {
        $this->authorizeResource(Pupil::class, 'pupil');
        $this->pupilRepository = $pupilRepository;
    }

    public function index(Request $request)
    {
        $pupilsOrdered = $this->pupilRepository->getForIndex($request->search);

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

    public function store(StorePupilRequest $request)
    {
        $pupil = $this->pupilRepository->createPupil($request->validated());
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
        $pupil = $this->pupilRepository->getPupilWithRelations($pupil);
        return Inertia::render('Pupil/CreateShow', [
            'pupil' => $pupil,
            'tags' => Tag::whereModel(Pupil::class)->get(['id', 'name'])
        ]);
    }

    public function update(UpdatePupilRequest $request, Pupil $pupil)
    {
        $this->pupilRepository->updatePupil($pupil, $request->validated());
        $tags = collect($request->tags)->pluck('id')->toArray();
        $pupil->tags()->sync($tags);

        session()->flash('flash.banner', __('messages.model_updated', ['model' => __('models.pupil')]));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('pupil.index');
    }

    public function destroy(Pupil $pupil)
    {
        $this->pupilRepository->deletePupil($pupil);

        session()->flash('flash.banner', __('messages.model_deleted', ['model' => __('models.pupil')]));
        session()->flash('flash.bannerStyle', 'success');
        return to_route('home');
    }
}
