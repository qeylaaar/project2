@section('title', 'Pemetaan Lokasi Bencana')
@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Pemetaan Lokasi Bencana</h6>
                </div>
                <div class="card-body px-3 pt-3 pb-2">
                    <form id="filterForm" class="mb-3" method="GET">
                        <div class="row align-items-end">
                            <div class="col-md-5 col-lg-4 mb-2 mb-md-0">
                                <label for="jenisBencana" class="form-label fw-bold">Filter Jenis Bencana</label>
                                <select id="jenisBencana" name="jenis_bencana" class="form-select shadow-sm">
                                    <option value="">Semua Jenis</option>
                                    @foreach($jenisBencanaList as $jenis)
                                        <option value="{{ $jenis }}">{{ $jenis }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                    <div id="map" style="height: 600px; width: 100%; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.07);"></div>
                    <div class="mt-4">
                        <h6 class="fw-bold mb-2">Keterangan Warna Pinpoint:</h6>
                        <ul id="legend-list" class="d-flex flex-wrap gap-3" style="list-style:none; padding-left:0;"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Daftar jenis bencana dan warna tetap (sesuai list user)
const jenisBencanaList = [
    'Banjir',
    'Longsor',
    'Kebakaran',
    'Angin Kencang',
    'Gempa Bumi',
    'Lainnya',
];
const warnaBencana = {
    'Banjir': '#007bff',        // biru
    'Longsor': '#795548',      // coklat
    'Kebakaran': '#e53935',   // merah
    'Angin Kencang': '#00bcd4', // cyan
    'Gempa Bumi': '#ff9800',  // oranye
    'Lainnya': '#607d8b',     // abu-abu
};
const markersData = @json($markers);

// Render legend
function renderLegend() {
    const legend = document.getElementById('legend-list');
    legend.innerHTML = '';
    jenisBencanaList.forEach(jenis => {
        const color = warnaBencana[jenis] || '#333';
        const li = document.createElement('li');
        li.className = 'd-flex align-items-center';
        li.innerHTML = `<span style="display:inline-block;width:20px;height:20px;background:${color};border-radius:50%;margin-right:8px;border:2px solid #fff;box-shadow:0 0 2px #333;"></span> <span style='font-size:15px;'>${jenis}</span>`;
        legend.appendChild(li);
    });
}

document.addEventListener("DOMContentLoaded", function () {
    renderLegend();
    const map = L.map('map').setView([-6.4885, 107.6838], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    let leafletMarkers = [];
    function showMarkers(filterJenis) {
        leafletMarkers.forEach(m => map.removeLayer(m));
        leafletMarkers = [];
        markersData.forEach(data => {
            if (!filterJenis || data.jenis === filterJenis) {
                const color = warnaBencana[data.jenis] || '#333';
                const icon = L.divIcon({
                    className: '',
                    html: `<span style='display:inline-block;width:28px;height:28px;background:${color};border-radius:50%;border:2px solid white;box-shadow:0 0 4px #333;'></span>`
                });
                const marker = L.marker([data.lat, data.lng], { icon });
                marker.bindPopup(`<b>${data.jenis}</b><br><b>Pelapor:</b> ${data.nama}<br><b>Alamat:</b> ${data.alamat}`);
                marker.addTo(map);
                leafletMarkers.push(marker);
            }
        });
    }
    showMarkers('');
    document.getElementById('jenisBencana').addEventListener('change', function(e) {
        showMarkers(this.value);
    });
    setTimeout(() => {
        map.invalidateSize();
    }, 200);
});
</script>
@endpush
