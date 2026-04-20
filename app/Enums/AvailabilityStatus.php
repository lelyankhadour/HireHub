<?php
namespace App\Enums;

enum AvailabilityStatus: string
{
    case Available = 'available';
    case Busy = 'busy';
    case Unavailable = 'unavailable';
}
