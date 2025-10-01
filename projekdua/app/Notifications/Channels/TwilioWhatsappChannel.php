<?php

namespace App\Notifications\Channels;

use App\Services\TwilioService;
use Illuminate\Notifications\Notification;
use App\Notifications\Contracts\TwilioWhatsappMessage;

class TwilioWhatsappChannel
{
    private TwilioService $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function send($notifiable, Notification $notification): void
    {
        if (!$notification instanceof TwilioWhatsappMessage) {
            return;
        }

        $toPhone = method_exists($notifiable, 'routeNotificationForTwilioWhatsapp')
            ? (string) $notifiable->routeNotificationForTwilioWhatsapp()
            : '';

        $message = (string) $notification->toTwilioWhatsapp($notifiable);

        if ($toPhone !== '' && $message !== '') {
            $this->twilio->sendWhatsapp($toPhone, $message);
        }
    }
}


