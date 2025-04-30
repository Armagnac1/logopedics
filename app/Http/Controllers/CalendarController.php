<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\Abstracts\CalendarRepositoryInterface;
use Illuminate\Http\Response;
use Inertia\Inertia;

class CalendarController extends Controller
{
    private $calendarRepository;

    public function __construct(CalendarRepositoryInterface $calendarRepository)
    {
        $this->calendarRepository = $calendarRepository;
    }

    public function index()
    {
        $tutorId = auth()->user()->tutor->id;
        $lessons = $this->calendarRepository->getLessonsForTutor($tutorId);
        return Inertia::render('Calendar', [
            'lessons' => $lessons
        ]);
    }

    public function generateCalendar(User $user)
    {
        $tutorId = auth()->user()->tutor->id;
        return $this->calendarRepository->generateCalendar($tutorId);
    }
}
