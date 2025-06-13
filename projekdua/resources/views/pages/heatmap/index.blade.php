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

<script>
    let map, markers = [];
    function initMap() {
        map = L.map('map', {
            center: { lat: -7.7925927, lng: 110.3658812 },
            zoom: 13
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        map.on('click', mapClicked);
        initMarkers();
    }

    function initMarkers() {
        const initialMarkers = @json($initialMarkers ?? []);
        for (let index = 0; index < initialMarkers.length; index++) {
            const data = initialMarkers[index];
            const marker = generateMarker(data, index);
            marker.addTo(map).bindPopup(`<b>${data.position.lat},  ${data.position.lng}</b>`);
            map.panTo(data.position);
            markers.push(marker)
        }
    }

    function generateMarker(data, index) {
        return L.marker(data.position, {
                draggable: data.draggable
            })
            .on('click', (event) => markerClicked(event, index))
            .on('dragend', (event) => markerDragEnd(event, index));
    }

    function mapClicked($event) {
        console.log(map);
        console.log($event.latlng.lat, $event.latlng.lng);
    }

    function markerClicked($event, index) {
        console.log(map);
        console.log($event.latlng.lat, $event.latlng.lng);
    }

    function markerDragEnd($event, index) {
        console.log(map);
        console.log($event.target.getLatLng());
    }

    document.addEventListener("DOMContentLoaded", function () {
        initMap();
        setTimeout(() => {
            map.invalidateSize();
        }, 200);
    });
</script>
@endpush
@endsection
