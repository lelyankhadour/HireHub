<?php

namespace App\Contracts;

interface NotificationInterface
{
    public function send($user,string $message):bool;
}