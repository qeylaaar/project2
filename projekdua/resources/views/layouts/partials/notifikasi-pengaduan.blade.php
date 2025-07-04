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
let lastPengaduanId = {{ isset($pengaduans) && $pengaduans->first() ? $pengaduans->first()->id : 0 }};
let isAlertShown = false;
setInterval(function() {
    if (isAlertShown) return;
    fetch("{{ url('/api/pengaduan/latest') }}")
        .then(res => res.json())
        .then(data => {
            if (data.id && data.id > lastPengaduanId) {
                isAlertShown = true;
                document.getElementById('notifSound').play().then(() => {
                    Swal.fire({
                        icon: 'info',
                        title: 'Laporan Baru!',
                        text: 'Ada laporan baru masuk.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = "{{ route('pengaduan.index') }}";
                    });
                }).catch(() => {
                    Swal.fire({
                        icon: 'info',
                        title: 'Laporan Baru!',
                        text: 'Ada laporan baru masuk.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = "{{ route('pengaduan.index') }}";
                    });
                });
            }
        });
}, 5000);
</script>
