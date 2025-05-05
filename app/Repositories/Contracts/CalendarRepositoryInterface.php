<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;

interface CalendarRepositoryInterface
{
    public function generateCalendar(int $tutorId): Response;

    public function getLessonsForTutor(int $tutorId): Collection;
}
