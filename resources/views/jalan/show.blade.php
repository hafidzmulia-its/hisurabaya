<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Route Details') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map {
            width: 100%;
            height: 600px;
            position: relative;
            z-index: 0;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-6">
                <!-- Details Column -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold mb-6">{{ $jalan->NamaJalan }}</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Start Point</p>
                                <p class="text-lg">{{ $jalan->startPoint->NamaObjek }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-700">End Point</p>
                                <p class="text-lg">{{ $jalan->endPoint->NamaObjek }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-700">Distance</p>
                                <p class="text-lg">{{ $jalan->DistanceKM }} KM</p>
                            </div>

                            @if($jalan->KoordinatJSON)
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-1">Route Coordinates</p>
                                <div class="bg-gray-100 p-3 rounded">
                                    <p class="text-xs text-gray-600">{{ is_array($jalan->KoordinatJSON) ? count($jalan->KoordinatJSON) : 0 }} waypoints</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="flex justify-end gap-2 mt-6">
                            <a href="{{ route('jalan.index') }}" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Back</a>
                            <a href="{{ route('jalan.edit', $jalan) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
                        </div>
                    </div>
                </div>

                <!-- Map Column -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Route Visualization</h3>
                        <div id="map" class="w-full h-[600px] rounded border"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                // Initialize map
                const map = L.map('map').setView([-7.2575, 112.7521], 13);
                setTimeout(() => map.invalidateSize(), 100);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Display route
        @if($jalan->KoordinatJSON)
            try {
                let coords = {!! json_encode($jalan->KoordinatJSON) !!};
                if (typeof coords === 'string') {
                    coords = JSON.parse(coords);
                }
                if (Array.isArray(coords) && coords.length > 0) {
                    const latLngs = coords.map(coord => [coord[1], coord[0]]);
                    const polyline = L.polyline(latLngs, {
                        color: 'blue',
                        weight: 4,
                        opacity: 0.7
                    }).addTo(map);

                    // Fit map to route bounds
                    map.fitBounds(polyline.getBounds());

                    // Add popup
                    polyline.bindPopup('<strong>{{ $jalan->NamaJalan }}</strong><br>{{ $jalan->DistanceKM }} KM');

                    // Add markers for start and end points
                    L.marker([{{ $jalan->startPoint->Latitude }}, {{ $jalan->startPoint->Longitude }}], {
                        icon: L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        })
                    }).addTo(map).bindPopup('<strong>Start:</strong> {{ $jalan->startPoint->NamaObjek }}');

                    L.marker([{{ $jalan->endPoint->Latitude }}, {{ $jalan->endPoint->Longitude }}], {
                        icon: L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        })
                    }).addTo(map).bindPopup('<strong>End:</strong> {{ $jalan->endPoint->NamaObjek }}');
                }
            } catch(e) {
                console.error('Failed to display route:', e);
            }
        @endif
            }, 100);
        });
    </script>
</x-app-layout>
