import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Fungsi untuk memainkan suara
function playNotificationSound() {
    const audio = new Audio('/sounds/notification.mp3');
    audio.volume = 1.0; // Set volume ke maksimum
    audio.play().catch(error => {
        console.error('Error playing sound:', error);
    });
}

// Fungsi untuk menampilkan notifikasi
function showNotification(data) {
    console.log('Notification received:', data);

    // Play sound terlebih dahulu
    playNotificationSound();

    // Kemudian tampilkan notifikasi browser
    if (Notification.permission === "granted") {
        const notification = new Notification("Laporan Bencana Baru", {
            body: `Laporan baru dari ${data.nama_pelapor || 'seseorang'} - ${data.jenis_pengaduan || 'Bencana'}`,
            icon: '/img/logobpbd.jpg',
            requireInteraction: true // Notifikasi tidak akan hilang sampai user mengklik
        });

        notification.onclick = function() {
            window.focus();
            // Reload halaman saat notifikasi diklik
            window.location.reload();
        };
    }
}

// Request permission untuk notifikasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    if (Notification.permission !== "granted") {
        Notification.requestPermission();
    }
});

// Setup Echo
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// Listen untuk notifikasi
console.log('Starting notification listener...');
const adminId = document.querySelector('meta[name="admin-id"]')?.content;

if (adminId) {
    console.log('Listening for notifications on channel:', `App.Models.User.${adminId}`);

    window.Echo.private(`App.Models.User.${adminId}`)
        .notification((notification) => {
            console.log('Notification received in listener:', notification);
            showNotification(notification);
        });
} else {
    console.error('Admin ID not found in meta tag');
}
