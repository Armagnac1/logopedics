<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Tutor;
use App\Models\User;
use Inertia\Inertia;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\DateTime as ICalDateTime;
use Illuminate\Support\Facades\Response;

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
        })->with(['pupil', 'pupil.tags'])->get();
        return Inertia::render('Calendar', [
            'lessons' => $lessons
        ]);
    }

    /**
     * Generate and return the iCalendar file.
     */
    public function generateCalendar(User $user)
    {
        $tutorId = $user->tutor->id;
        $events = Lesson::whereHas('pupil', function ($query) use ($tutorId) {
            $query->where('tutor_id', $tutorId);
        })->with(['pupil', 'pupil.tags'])->get();

        $calendar = new Calendar();

        foreach ($events as $e) {
            $event = new Event();

            $summary = $e->pupil->full_name;
            $event->setSummary($summary);

            $description = $e->title ?? '';
            $event->setDescription($description);
            if (!$e->start_at) {
                continue;
            }
            $startDateTime = new \DateTime($e->start_at);
            $endDateTime = clone $startDateTime;
            $endDateTime->modify("+{$e->duration} minutes");

            $applyTimeZone = false;

            $event->setOccurrence(
                new \Eluceo\iCal\Domain\ValueObject\TimeSpan(
                    new ICalDateTime($startDateTime, $applyTimeZone),
                    new ICalDateTime($endDateTime, $applyTimeZone)
                )
            );
            $calendar->addEvent($event);
        }

        $componentFactory = new \Eluceo\iCal\Presentation\Factory\CalendarFactory();
        $calendarComponent = $componentFactory->createCalendar($calendar);

        return Response::make($calendarComponent, 200, [
            'Content-Type' => 'text/calendar',
            'Content-Disposition' => 'inline; filename="' . config("app.name") . '.ics"',
        ]);
    }
}
