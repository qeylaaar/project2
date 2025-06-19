<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Notifications\NewDisasterReport;

class PengaduanController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function index()
    {
        $pengaduans = Pengaduan::latest()->get();
        $pengaduans->transform(function ($item) {
            $item->bukti = $item->bukti ? json_decode($item->bukti, true) : [];
            return $item;
        });
        return response()->json([
            'success' => true,
            'data' => $pengaduans
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelapor' => 'required|string',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'jenis_pengaduan' => 'required|string',
            'kecamatan' => 'required|string',
            'desa' => 'required|string',
            'alamat' => 'required|string',
            'deskripsi' => 'nullable|string',
            'status' => 'required|string',
            'user_id' => 'required|integer',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:10240',
        ]);

        $mediaUris = [];
        $mediaTypes = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('bukti', 'public');
                $mediaUris[] = 'storage/' . $path;
                $mediaTypes[] = $file->getMimeType();
            }
        }

        $pengaduan = Pengaduan::create([
            'user_id' => $request->user_id,
            'nama_pelapor' => $request->nama_pelapor,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'jenis_pengaduan' => $request->jenis_pengaduan,
            'kecamatan' => $request->kecamatan,
            'desa' => $request->desa,
            'alamat' => $request->alamat,
            'media_uri' => json_encode($mediaUris),
            'media_type' => json_encode($mediaTypes),
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ]);

        try {
            Log::info('Mengirim notifikasi ke admin...');
            $admin = User::where('role', 'admin')->first();

            if ($admin) {
                Log::info('Admin ditemukan dengan ID: ' . $admin->id_user);
                $admin->notify(new NewDisasterReport($pengaduan));
                Log::info('Notifikasi berhasil dikirim ke admin');
            } else {
                Log::error('Admin tidak ditemukan');
            }
        } catch (\Exception $e) {
            Log::error('Error saat mengirim notifikasi: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim',
            'data' => $pengaduan
        ], 201);
    }

    public function show(Pengaduan $pengaduan)
    {
        $data = $pengaduan->toArray();
        $data['bukti'] = $pengaduan->bukti ? json_decode($pengaduan->bukti, true) : [];
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function update(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_pelapor' => 'required|string|max:255',
            'jenis_pengaduan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'status' => 'required|in:Menunggu,Proses,Selesai',
            'feedback' => 'nullable|string',
            'bukti' => 'nullable|string'
        ]);

        $oldStatus = $pengaduan->status;
        $pengaduan->update($request->all());

        // Kirim notifikasi jika status berubah atau ada feedback baru
        if ($oldStatus !== $request->status || $request->feedback) {
            $this->sendStatusUpdateNotification($pengaduan);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil diperbarui',
            'data' => $pengaduan
        ]);
    }

    private function sendStatusUpdateNotification($pengaduan)
    {
        try {
            $user = User::where('id', $pengaduan->user_id)->first();
            if (!$user || !$user->fcm_token) {
                return;
            }

            $title = 'Update Status Pengaduan';
            $body = "Pengaduan Anda dengan jenis {$pengaduan->jenis_pengaduan} telah diubah statusnya menjadi {$pengaduan->status}";

            if ($pengaduan->feedback) {
                $body .= "\nFeedback: {$pengaduan->feedback}";
            }

            $data = [
                'pengaduan_id' => $pengaduan->id,
                'status' => $pengaduan->status,
                'feedback' => $pengaduan->feedback,
                'bukti' => $pengaduan->bukti
            ];

            $this->firebaseService->sendNotification($user->fcm_token, $title, $body, $data);
        } catch (\Exception $e) {
            Log::error('Error sending notification: ' . $e->getMessage());
        }
    }

    public function destroy(Pengaduan $pengaduan)
    {
        $pengaduan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil dihapus'
        ]);
    }

    public function byUser($user_id)
    {
        try {
            $pengaduan = Pengaduan::where('user_id', $user_id)
                                 ->orderBy('created_at', 'desc')
                                 ->get();
            $pengaduan->transform(function ($item) {
                $item->bukti = $item->bukti ? json_decode($item->bukti, true) : [];
                return $item;
            });
            if ($pengaduan->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }
            return response()->json([
                'success' => true,
                'data' => $pengaduan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data pengaduan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadMedia(Request $request)
    {
        // Tambahkan log untuk debugging
        Log::info('Headers:', $request->headers->all());
        Log::info('Content-Type:', $request->header('Content-Type'));

        // Jangan validasi Content-Type secara manual!
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $url = asset('uploads/'.$filename);
            return response()->json(['success' => true, 'url' => $url]);
        }
        return response()->json([
            'success' => false,
            'error' => 'No file uploaded',
            'content_type' => $request->header('Content-Type'),
            'files' => $request->allFiles(),
            'all' => $request->all(),
        ], 400);
    }
}
