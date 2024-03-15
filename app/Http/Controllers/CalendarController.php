<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Inertia\Inertia;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tutorId = auth()->user()->tutor->id;
        $lessons = Lesson::whereHas('pupil', function ($query) use ($tutorId) {
            $query->where('tutor_id', $tutorId);
        })->with('pupil')->get();
        return Inertia::render('Calendar', [
            'lessons' => $lessons
        ]);
    }
}
