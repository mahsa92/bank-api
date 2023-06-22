<?php

namespace App\Services\Notifier;

use App\Services\Notifier\Strategies\NotificationStrategy;

class Notifier
{
    private NotificationStrategy $strategy;

    public function setStrategy(NotificationStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function notify(string $recipient, string $message): void
    {
        $this->strategy->notify($recipient, $message);
    }
}
