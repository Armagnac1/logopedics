<?php

namespace App\Repositories\Eloquent;

use App\Models\Lesson;
use App\Repositories\Contracts\CalendarRepositoryInterface;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\ValueObject\DateTime as ICalDateTime;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response as ResponseFacade;

class CalendarRepository implements CalendarRepositoryInterface
{
    public function generateCalendar(int $tutorId): Response
    {
        $events = $this->getLessonsForTutor($tutorId);

        $calendar = new Calendar();

        foreach ($events as $e) {
            if (! $e->start_at) {
                continue;
            }

            $event = new Event();
            $event->setSummary($e->pupil->full_name);
            $event->setDescription($e->title ?? '');

            $startDateTime = new \DateTime($e->start_at);
            $endDateTime = (clone $startDateTime)->modify("+{$e->duration} minutes");

            $event->setOccurrence(
                new \Eluceo\iCal\Domain\ValueObject\TimeSpan(
                    new ICalDateTime($startDateTime, false),
                    new ICalDateTime($endDateTime, false)
                )
            );

            $calendar->addEvent($event);
        }

        $calendarComponent = (new \Eluceo\iCal\Presentation\Factory\CalendarFactory())->createCalendar($calendar);

        return ResponseFacade::make($calendarComponent, 200, [
            'Content-Type' => 'text/calendar',
            'Content-Disposition' => 'inline; filename="'.config('app.name').'.ics"',
        ]);
    }

    public function getLessonsForTutor(int $tutorId): \Illuminate\Support\Collection
    {
        return Lesson::whereHas('pupil', function ($query) use ($tutorId) {
            $query->where('tutor_id', $tutorId);
        })->with(['pupil', 'pupil.tags'])->get();
    }
}
