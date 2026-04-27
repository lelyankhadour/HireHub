<?php

namespace App\Services\Notifications;

use App\Contracts\NotificationInterface;
use Illuminate\Support\Facades\Mail;

class EmailNotificationDriver implements NotificationInterface
{
    public function send($user, string $message): bool
    {
        Mail::raw($message, function ($mail) use ($user) {
            $mail->to($user->email)
                 ->subject('new updates');
        });

        return true;
    }
}
