<?php

namespace App\Notifications\Channels;

use App\Services\TwilioService;
use Illuminate\Notifications\Notification;
use App\Notifications\Contracts\TwilioSmsMessage;

class TwilioSmsChannel
{
    private TwilioService $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function send($notifiable, Notification $notification): void
    {
        if (!$notification instanceof TwilioSmsMessage) {
            return;
        }

        $toPhone = method_exists($notifiable, 'routeNotificationForTwilioSms')
            ? (string) $notifiable->routeNotificationForTwilioSms()
            : '';

        $message = (string) $notification->toTwilioSms($notifiable);

        if ($toPhone !== '' && $message !== '') {
            $this->twilio->sendSms($toPhone, $message);
        }
    }
}


