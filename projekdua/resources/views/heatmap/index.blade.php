@section('title', 'Heatmap Deteksi Bencana')
@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Heatmap Deteksi Bencana</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div id="map" style="height: 600px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Leaflet.heat plugin -->
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Inisialisasi peta
        const map = L.map('map').setView([-6.488572949806837, 107.6838299716267], 14);

        // Tambahkan layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Data heatmap dari controller
        const heatData = @json($heatmapData);

        // Tambahkan layer heatmap
        L.heatLayer(heatData, {
            radius: 30,
            blur: 30,
            maxZoom: 18,
            max: 2.5, // Sesuaikan dengan nilai weight tertinggi
            gradient: {
                0.4: 'blue',
                0.6: 'lime',
                0.8: 'yellow',
                1.0: 'red'
            }
        }).addTo(map);

        // Pastikan peta ter-render dengan benar
        setTimeout(() => {
            map.invalidateSize();
        }, 200);
    });
</script>
@endpush
@endsection
