@props([
    'lat' => 14.442312,
    'long' => 121.396931,
    'zoom' => 14,
])

<div>
    <div id="map" class="border rounded-lg h-96 border-slate-200"></div>

    {{-- Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

    <script>
        var map = L.map('map').setView([{{ $lat }}, {{ $long }}], {{ $zoom }});

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([{{ $lat }}, {{ $long }}]).addTo(map)
            .bindPopup('Amazing View!');
    </script>
</div>