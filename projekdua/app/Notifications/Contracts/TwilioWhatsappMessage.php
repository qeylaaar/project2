<?php

namespace App\Notifications\Contracts;

interface TwilioWhatsappMessage
{
    public function toTwilioWhatsapp($notifiable): string;
}


