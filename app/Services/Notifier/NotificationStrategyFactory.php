<?php

namespace App\Services\Notifier;

use App\Services\Notifier\Strategies\GhasedakNotificationStrategy;
use App\Services\Notifier\Strategies\KavenegarNotificationStrategy;
use App\Services\Notifier\Strategies\NotificationStrategy;
use InvalidArgumentException;

class NotificationStrategyFactory
{
    public static function create(): NotificationStrategy
    {
        $provider = env('SMS_PROVIDER');
        return match ($provider) {
            'kavenegar' => new KavenegarNotificationStrategy(),
            'ghasedak' => new GhasedakNotificationStrategy(),
             default => throw new InvalidArgumentException('Invalid SMS provider.')
        };
    }
}
