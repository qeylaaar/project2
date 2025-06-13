<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $serverKey;

    public function __construct()
    {
        $this->serverKey = config('services.firebase.server_key');
    }

    public function sendNotification($token, $title, $body, $data = [])
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $this->serverKey,
                'Content-Type' => 'application/json'
            ])->post('https://fcm.googleapis.com/fcm/send', [
                'to' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'sound' => 'default'
                ],
                'data' => $data
            ]);

            if (!$response->successful()) {
                Log::error('Firebase notification failed: ' . $response->body());
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Firebase notification error: ' . $e->getMessage());
            return false;
        }
    }
}
