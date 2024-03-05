<?php

namespace App\Enums;

enum LessonStatus: int
{
    case CREATED = 0;
    case SCHEDULED = 1;
    case FINISHED = 2;
}
