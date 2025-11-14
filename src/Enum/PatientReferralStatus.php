<?php

namespace App\Enum;

enum PatientReferralStatus: string
{
    case Accepted = 'ACCEPTED';
    case Pending = 'PENDING';
    case Scheduled = 'SCHEDULED';
}