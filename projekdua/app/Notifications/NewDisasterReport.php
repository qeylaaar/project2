<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewDisasterReport extends Notification implements ShouldQueue
{
    use Queueable;

    protected $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Laporan bencana baru telah diterima',
            'report_id' => $this->report->id,
            'location' => $this->report->location,
            'type' => $this->report->type,
            'created_at' => now()
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'Laporan bencana baru telah diterima',
            'report_id' => $this->report->id,
            'location' => $this->report->location,
            'type' => $this->report->type,
            'created_at' => now()
        ]);
    }
}