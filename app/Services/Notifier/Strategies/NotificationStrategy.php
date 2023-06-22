<?php

namespace App\Services\Notifier\Strategies;

interface NotificationStrategy
{
    public function notify(string $recipient, string $message): void;
}
