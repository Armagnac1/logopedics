<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\CalendarRepositoryInterface;
use App\Repositories\Cached\CachedUserRepository;
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
        $tutorId = app()->make(CachedUserRepository::class)->getTutorId(auth()->id());
        $lessons = $this->calendarRepository->getLessonsForTutor($tutorId);

        return Inertia::render('Calendar', [
            'lessons' => $lessons,
        ]);
    }

    public function generateCalendar(User $user)
    {
        $tutorId = app()->make(CachedUserRepository::class)->getTutorId(auth()->id());

        return $this->calendarRepository->generateCalendar($tutorId);
    }
}
