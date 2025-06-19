<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewDisasterReport extends Notification implements ShouldQueue
{
    use Queueable;

    protected $pengaduan;

    public function __construct($pengaduan)
    {
        $this->pengaduan = $pengaduan;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function databaseType()
    {
        return 'App\Models\User';
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Laporan bencana baru dari ' . $this->pengaduan->nama_pelapor,
            'pengaduan_id' => $this->pengaduan->id,
            'nama_pelapor' => $this->pengaduan->nama_pelapor,
            'jenis_pengaduan' => $this->pengaduan->jenis_pengaduan,
            'lokasi' => $this->pengaduan->alamat,
            'created_at' => now()
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'Laporan bencana baru dari ' . $this->pengaduan->nama_pelapor,
            'pengaduan_id' => $this->pengaduan->id,
            'nama_pelapor' => $this->pengaduan->nama_pelapor,
            'jenis_pengaduan' => $this->pengaduan->jenis_pengaduan,
            'lokasi' => $this->pengaduan->alamat,
            'created_at' => now()
        ]);
    }
}
