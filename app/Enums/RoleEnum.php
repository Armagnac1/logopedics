<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SUPERADMIN = 'superadmin';
    case ADMIN = 'admin';
    case TUTOR = 'tutor';
    case TUTOR_SELLER = 'tutor-seller';
    case PUPIL = 'pupil';
}
