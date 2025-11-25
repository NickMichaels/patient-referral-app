<?php

namespace App\Enum;

enum ScheduleDayOfWeek: string
{
    case Monday = 'MONDAY';
    case Tuesday = 'TUESDAY';
    case Wednesday = 'WEDNESDAY';
    case Thursday = 'THURSDAY';
    case Friday = 'FRIDAY';
    case Saturday = 'SATURDAY';
    case Sunday = 'SUNDAY';
}
