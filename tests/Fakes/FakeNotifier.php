<?php

namespace Tests\Fakes;

use App\Contracts\NotificationInterface;



class FakeNotifier implements NotificationInterface
{
    public array $sent = [];

    public function send($user, string $message): bool
    {
        $this->sent[] = [
            'user' => $user,
            'message' => $message,
        ];

        return true;
    }
}
