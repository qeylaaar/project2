@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
<audio id="notifSound" src="{{ asset('sounds/notification.mp3') }}" preload="auto"></audio>
<script>
// Unlock audio agar bisa autoplay setelah interaksi user pertama
if (typeof window.notifUnlock === 'undefined') {
    document.addEventListener('click', function enableAudioOnce() {
        var audio = document.getElementById('notifSound');
        audio.play().then(() => {
            audio.pause();
            audio.currentTime = 0;
            document.removeEventListener('click', enableAudioOnce);
        }).catch(()=>{});
    });
    window.notifUnlock = true;
}

function playNotifLoop() {
    var audio = document.getElementById('notifSound');
    if (!audio) return function(){};

    var stopped = false;
    var intervalId;

    function playAudio() {
        if (stopped) return;
        console.log('Playing loop audio...');

        // Reset dan putar audio
        audio.currentTime = 0;
        audio.volume = 1.0;
        audio.muted = false;
        audio.play();
    }

    // Mulai putar pertama kali
    playAudio();

    // Set interval untuk loop setiap 16 detik (menyesuaikan durasi audio 15s)
    intervalId = setInterval(playAudio, 16000);
    console.log('Loop interval started');

    function stop() {
        if (stopped) return;
        stopped = true;
        console.log('Stopping audio loop');

        if (intervalId) clearInterval(intervalId);
        audio.pause();
        audio.currentTime = 0;
    }

    return stop;
}

console.log('Script notifikasi loaded');
let lastPengaduanId = {{ isset($pengaduans) && $pengaduans->first() ? $pengaduans->first()->id : 0 }};
let isAlertShown = false;

console.log('Last pengaduan ID:', lastPengaduanId);

setInterval(function() {
    console.log('Checking for new reports...');
    if (isAlertShown) {
        console.log('Alert already shown, skipping...');
        return;
    }

    fetch("{{ url('/api/pengaduan/latest') }}")
        .then(res => res.json())
        .then(data => {
            console.log('Fetched data:', data);
            if (data.id && data.id > lastPengaduanId) {
                console.log('New report detected! ID:', data.id, 'Last ID:', lastPengaduanId);
                isAlertShown = true;

                // Mulai audio seketika sebelum prompt tampil untuk menghindari delay
                if (window.__notifStop) {
                    try { window.__notifStop(); } catch (e) {}
                }
                window.__notifStop = playNotifLoop();

                // Tampilkan prompt
                Swal.fire({
                    icon: 'info',
                    title: 'Laporan Baru!',
                    text: 'Ada laporan baru masuk.',
                    confirmButtonText: 'OK',
                    // Tidak perlu start di didOpen karena sudah diputar sebelum prompt
                    willClose: () => {
                        console.log('Prompt closing, stopping audio loop');
                        if (window.__notifStop) {
                            try { window.__notifStop(); } catch (e) {}
                            window.__notifStop = null;
                        }
                    }
                }).then(() => {
                    // Pastikan audio berhenti saat user menekan OK
                    if (window.__notifStop) {
                        try { window.__notifStop(); } catch (e) {}
                        window.__notifStop = null;
                    }
                    window.location.href = "{{ route('pengaduan.index') }}";
                });
            } else {
                console.log('No new reports found');
            }
        })
        .catch(error => {
            console.log('Error fetching data:', error);
        });
}, 5000);
</script>
