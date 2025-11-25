<?php

namespace App\Enum;

enum AppointmentStatus: string
{
    case Scheduled = 'SCHEDULED';
    case Completed = 'COMPLETED';
    case Cancelled = 'CANCELLED';
}
