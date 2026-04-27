<?php
namespace App\Enums;

enum UserRole: string
{
    case Client = 'client';
    case Freelancer = 'freelancer';

}