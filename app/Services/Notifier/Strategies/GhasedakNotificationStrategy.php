<?php

namespace App\Services\Notifier\Strategies;

use Illuminate\Support\Facades\Log;

class GhasedakNotificationStrategy implements NotificationStrategy
{
    public function notify(string $recipient, string $message): void
    {
        try{

        }
        catch(ApiException|HttpException $e){
            Log::error($e->errorMessage());
        }
    }
}
