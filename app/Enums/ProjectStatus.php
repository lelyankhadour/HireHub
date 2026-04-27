<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case Open = 'open';
    case InProgress = 'in_progress';
    case Closed = 'closed';

    public function isOpenForEdit(): bool
    {
        return $this === self::Open;
    }
}