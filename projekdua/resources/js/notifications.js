import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// Fungsi untuk memainkan suara notifikasi
function playNotificationSound() {
    const audio = new Audio('/sounds/notification.mp3');
    audio.play();
}

// Fungsi untuk menampilkan notifikasi
function showNotification(data) {
    if (Notification.permission === "granted") {
        const notification = new Notification("Laporan Bencana Baru", {
            body: data.message,
            icon: '/images/logo.png'
        });

        notification.onclick = function() {
            window.focus();
            window.location.href = `/admin/reports/${data.report_id}`;
        };
    }
}

// Request permission untuk notifikasi
if (Notification.permission !== "granted" && Notification.permission !== "denied") {
    Notification.requestPermission();
}

// Listen untuk notifikasi baru
window.Echo.private('App.Models.User.1')
    .notification((notification) => {
        playNotificationSound();
        showNotification(notification);

        // Update badge notifikasi jika ada
        const badge = document.getElementById('notification-badge');
        if (badge) {
            const currentCount = parseInt(badge.textContent) || 0;
            badge.textContent = currentCount + 1;
        }
    });