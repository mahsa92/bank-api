<?php

namespace App\Services\Notifier\Strategies;

use Illuminate\Support\Facades\Log;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;
use Kavenegar\Laravel\Facade as Kavenegar;

class KavenegarNotificationStrategy implements NotificationStrategy
{
    public function notify(string $recipient, string $message): void
    {
        try{
            Kavenegar::Send(env('SMS_SENDER'),$recipient,$message);
        }
        catch(ApiException|HttpException $e){
            Log::error($e->errorMessage());
        }
    }
}
