<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;
use App\Models\Lesson;
use App\Models\Pupil;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchInput = $request->input('search');
        if (!$searchInput) {
            return response()->json([
                'result' => [
                    'pupils' => Pupil::limit(5)->with(['user'])->get(),
                    'lessons' => Lesson::limit(5)->get(),
                    'learningMaterials' => LearningMaterial::limit(5)->with(['tags'])->get(),
                ]]);
        }
        $pupils = Pupil::search($searchInput)->query(function ($builder) {
            $builder->with(['user']);
        })->take(5)->get();
        $lessons = Lesson::search($searchInput)->take(5)->get();
        $learningMaterials = LearningMaterial::search($searchInput)->query(function ($builder) {
            $builder->with(['tags']);
        })->take(5)->get();
        return response()->json([
            'result' => [
                'pupils' => $pupils,
                'lessons' => $lessons,
                'learningMaterials' => $learningMaterials,
            ]]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
