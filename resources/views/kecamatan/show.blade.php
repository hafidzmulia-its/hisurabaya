<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kecamatan Details') }}
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
                        <h3 class="text-2xl font-bold mb-6">{{ $kecamatan->NamaKecamatan }}</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Wilayah</p>
                                <p class="text-lg">{{ $kecamatan->Wilayah }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-700">Number of Hotels</p>
                                <p class="text-lg">{{ $kecamatan->hotels->count() }}</p>
                            </div>

                            @if($kecamatan->hotels->count() > 0)
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-2">Hotels in this Kecamatan</p>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($kecamatan->hotels as $hotel)
                                        <li class="text-sm">{{ $hotel->NamaObjek }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @if($kecamatan->PolygonJSON)
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-1">Polygon Coordinates</p>
                                <div class="bg-gray-100 p-3 rounded">
                                    <p class="text-xs text-gray-600">{{ is_array($kecamatan->PolygonJSON) ? count($kecamatan->PolygonJSON) : 0 }} vertices</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="flex justify-end gap-2 mt-6">
                            <a href="{{ route('kecamatan.index') }}" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Back</a>
                            <a href="{{ route('kecamatan.edit', $kecamatan) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
                        </div>
                    </div>
                </div>

                <!-- Map Column -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Kecamatan Boundary</h3>
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

        // Display polygon
        @if($kecamatan->PolygonJSON)
            try {
                let coords = {!! json_encode($kecamatan->PolygonJSON) !!};
                if (typeof coords === 'string') {
                    coords = JSON.parse(coords);
                }
                if (Array.isArray(coords) && coords.length > 0) {
                    const latLngs = coords.map(coord => [coord[1], coord[0]]);
                    const polygon = L.polygon(latLngs, {
                        color: 'blue',
                        fillColor: '#3b82f6',
                        fillOpacity: 0.3,
                        weight: 2
                    }).addTo(map);

                    // Fit map to polygon bounds
                    map.fitBounds(polygon.getBounds());

                    // Add popup
                    polygon.bindPopup('<strong>{{ $kecamatan->NamaKecamatan }}</strong><br>{{ $kecamatan->Wilayah }}');
                }
            } catch(e) {
                console.error('Failed to display polygon:', e);
            }
        @endif

        // Display hotels in this kecamatan
        @foreach($kecamatan->hotels as $hotel)
            @if($hotel->Latitude && $hotel->Longitude)
                L.marker([{{ $hotel->Latitude }}, {{ $hotel->Longitude }}])
                    .addTo(map)
                    .bindPopup('<strong>{{ $hotel->NamaObjek }}</strong>');
            @endif
        @endforeach
            }, 100);
        });
    </script>
</x-app-layout>
