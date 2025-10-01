<?php

namespace App\Notifications\Contracts;

interface TwilioSmsMessage
{
    public function toTwilioSms($notifiable): string;
}


