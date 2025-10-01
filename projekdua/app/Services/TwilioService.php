<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
class TwilioService
{
    private $client;
    private string $fromSmsNumber;
    private string $fromWhatsappNumber;

    public function __construct()
    {
        $sid = (string) config('services.twilio.sid');
        $token = (string) config('services.twilio.token');
        $this->fromSmsNumber = (string) config('services.twilio.from');
        $this->fromWhatsappNumber = (string) config('services.twilio.whatsapp_from');

        $clientClass = '\\Twilio\\Rest\\Client';
        if (class_exists($clientClass)) {
            $this->client = new $clientClass($sid, $token);
        } else {
            $this->client = null;
        }
    }

    public function sendSms(string $toE164Phone, string $message): ?string
    {
        if (empty($toE164Phone) || empty($this->fromSmsNumber)) {
            Log::warning('Twilio SMS skipped: missing destination or from', [
                'to' => $toE164Phone,
                'from' => $this->fromSmsNumber,
            ]);
            return null;
        }

        if ($this->client === null) {
            Log::error('Twilio client not initialized. Check TWILIO_SID/TWILIO_TOKEN.');
            return null;
        }

        try {
            $msg = $this->client->messages->create($toE164Phone, [
                'from' => $this->fromSmsNumber,
                'body' => $message,
            ]);
            $sid = null;
            try {
                // Twilio MessageInstance exposes magic properties via __get
                $sid = isset($msg->sid) ? $msg->sid : null;
            } catch (\Throwable $e) {
                $sid = null;
            }
            Log::info('Twilio SMS sent', ['to' => $toE164Phone, 'sid' => $sid]);
            return $sid;
        } catch (\Throwable $e) {
            Log::error('Twilio SMS send failed: ' . $e->getMessage(), [
                'to' => $toE164Phone,
            ]);
            return null;
        }
    }

    public function sendWhatsapp(string $toE164Phone, string $message): void
    {
        if (empty($toE164Phone) || empty($this->fromWhatsappNumber)) {
            return;
        }

        if ($this->client === null) {
            return;
        }

        $this->client->messages->create('whatsapp:' . $toE164Phone, [
            'from' => $this->fromWhatsappNumber,
            'body' => $message,
        ]);
    }
}


