<x-app-layout>
    @push('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        #map {
            height: calc(100vh - 5rem);
            width: 100%;
        }
        
        .filter-panel {
            position: fixed;
            top: 5rem;
            right: 0;
            height: calc(100vh - 5rem);
            width: 400px;
            background: white;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .filter-panel.hidden-panel {
            transform: translateX(100%);
        }
        
        .toggle-button {
            position: fixed;
            top: 50%;
            right: 400px;
            transform: translateY(-50%);
            background: white;
            border: 1px solid #e5e7eb;
            border-right: none;
            border-radius: 0.5rem 0 0 0.5rem;
            padding: 1rem 0.5rem;
            cursor: pointer;
            z-index: 1001;
            transition: right 0.3s ease;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
        }
        
        .toggle-button.panel-hidden {
            right: 0;
        }
        
        @media (max-width: 768px) {
            .filter-panel {
                width: 100%;
            }
            
            .toggle-button {
                right: calc(100%);
            }
            
            .toggle-button.panel-hidden {
                right: 0;
            }
        }
    </style>
    @endpush

    <!-- Toggle Button -->
    <button id="toggleFilter" class="toggle-button">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </button>

    <!-- Filter Panel -->
    <div id="filterPanel" class="filter-panel">
        <div class="p-6">
            <h2 class="text-2xl font-bold mb-6">Choose your map view</h2>
            
            <!-- Map Mode Tabs -->
            <div class="flex gap-2 mb-6">
                <button id="btnPoint" class="flex-1 px-4 py-2 rounded bg-golden text-dark-gray font-semibold">
                    Point
                </button>
                <button id="btnRoute" class="flex-1 px-4 py-2 rounded bg-gray-700 text-white font-semibold">
                    Route
                </button>
                <button id="btnArea" class="flex-1 px-4 py-2 rounded bg-gray-700 text-white font-semibold">
                    Area
                </button>
            </div>
            
            <!-- Point Mode Filters -->
            <div id="pointFilters" class="space-y-4">
                <!-- Price Range -->
                <div>
                    <label class="block text-sm font-medium mb-2">Price Range</label>
                    <div class="mb-2">
                        <label class="text-xs text-gray-600">Min Price</label>
                        <input type="range" id="priceMin" class="w-full" min="0" max="10000000" step="100000" value="0">
                        <span id="priceMinValue" class="text-xs text-gray-600">Rp 0,00</span>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600">Max Price</label>
                        <input type="range" id="priceMax" class="w-full" min="0" max="10000000" step="100000" value="10000000">
                        <span id="priceMaxValue" class="text-xs text-gray-600">Rp 10.000.000,00</span>
                    </div>
                </div>
                
                <!-- Rating Checkboxes -->
                <div>
                    <label class="block text-sm font-medium mb-2">Rating</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 rating-filter" value="5">
                            <span class="flex">
                                ★★★★★
                            </span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 rating-filter" value="4">
                            <span class="flex">
                                ★★★★☆
                            </span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 rating-filter" value="3">
                            <span class="flex">
                                ★★★☆☆
                            </span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 rating-filter" value="2">
                            <span class="flex">
                                ★★☆☆☆
                            </span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 rating-filter" value="1">
                            <span class="flex">
                                ★☆☆☆☆
                            </span>
                        </label>
                    </div>
                </div>
                
                <!-- Area Checkboxes -->
                <div>
                    <label class="block text-sm font-medium mb-2">Area</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 area-filter" value="Surabaya Tengah">
                            <span>Surabaya Tengah</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 area-filter" value="Surabaya Barat">
                            <span>Surabaya Barat</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 area-filter" value="Surabaya Timur">
                            <span>Surabaya Timur</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 area-filter" value="Surabaya Utara">
                            <span>Surabaya Utara</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 area-filter" value="Surabaya Selatan">
                            <span>Surabaya Selatan</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Route Mode -->
            <div id="routeFilters" class="space-y-4 hidden">
                <p class="text-sm text-gray-600">Click on hotels on the map to select start and end points</p>
                <div>
                    <label class="block text-sm font-medium mb-2">Start Point</label>
                    <input type="text" id="startPoint" readonly class="w-full px-3 py-2 border rounded bg-gray-50" placeholder="Click on a hotel">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">End Point</label>
                    <input type="text" id="endPoint" readonly class="w-full px-3 py-2 border rounded bg-gray-50" placeholder="Click on a hotel">
                </div>
                <button id="findRoute" class="w-full bg-dark-gray text-white px-4 py-2 rounded font-semibold hover:bg-opacity-90 transition">
                    Find Route
                </button>
                <button id="clearRoute" class="w-full bg-gray-200 text-dark-gray px-4 py-2 rounded font-semibold hover:bg-gray-300 transition">
                    Clear
                </button>
                <div id="routeDistance" class="hidden mt-4 p-4 bg-golden rounded">
                    <p class="font-bold text-dark-gray">Distance: <span id="distanceValue"></span> km</p>
                </div>
            </div>
            
            <!-- Area Mode -->
            <div id="areaFilters" class="space-y-4 hidden">
                <div>
                    <label class="block text-sm font-medium mb-2">Show Wilayah</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 wilayah-filter" value="Surabaya Tengah">
                            <span>Surabaya Tengah</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 wilayah-filter" value="Surabaya Barat">
                            <span>Surabaya Barat</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 wilayah-filter" value="Surabaya Timur">
                            <span>Surabaya Timur</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 wilayah-filter" value="Surabaya Utara">
                            <span>Surabaya Utara</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 wilayah-filter" value="Surabaya Selatan">
                            <span>Surabaya Selatan</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Container -->
    <div id="map"></div>

    @push('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        // Initialize map
        const map = L.map('map').setView([-7.2575, 112.7521], 12);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Custom icon - SVG embedded directly
        const svgIcon = `<svg width="37" height="48" viewBox="0 0 37 48" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M18.5 0C26.1544 0 32.375 6.08 32.375 13.6C32.375 23.7943 18.5 38.8571 18.5 38.8571C18.5 38.8571 4.625 23.7943 4.625 13.6C4.625 6.08 10.8456 0 18.5 0ZM18.5 9.14286C17.2734 9.14286 16.097 9.62449 15.2296 10.4818C14.3623 11.3391 13.875 12.5019 13.875 13.7143C13.875 14.9267 14.3623 16.0895 15.2296 16.9468C16.097 17.8041 17.2734 18.2857 18.5 18.2857C19.7266 18.2857 20.903 17.8041 21.7704 16.9468C22.6377 16.0895 23.125 14.9267 23.125 13.7143C23.125 12.5019 22.6377 11.3391 21.7704 10.4818C20.903 9.62449 19.7266 9.14286 18.5 9.14286ZM37 38.8571C37 43.9086 28.7213 48 18.5 48C8.27875 48 0 43.9086 0 38.8571C0 35.9086 2.82125 33.28 7.19188 31.6114L8.67188 33.6914C6.17438 34.72 4.625 36.1371 4.625 37.7143C4.625 40.8686 10.8456 43.4286 18.5 43.4286C26.1544 43.4286 32.375 40.8686 32.375 37.7143C32.375 36.1371 30.8256 34.72 28.3281 33.6914L29.8081 31.6114C34.1787 33.28 37 35.9086 37 38.8571Z" fill="#3A3A3A"/>
</svg>`;
        const customIcon = L.icon({
            iconUrl: 'data:image/svg+xml;base64,' + btoa(svgIcon),
            iconSize: [37, 48],
            iconAnchor: [18, 48],
            popupAnchor: [0, -48]
        });
        
        // Layer groups
        let hotelsLayer = L.layerGroup().addTo(map);
        let routesLayer = L.layerGroup().addTo(map);
        let areasLayer = L.layerGroup().addTo(map);
        
        // State
        let currentMode = 'point';
        let routePoints = { start: null, end: null };
        let allHotels = [];
        
        // Toggle filter panel
        document.getElementById('toggleFilter').addEventListener('click', function() {
            const panel = document.getElementById('filterPanel');
            const btn = this;
            panel.classList.toggle('hidden-panel');
            btn.classList.toggle('panel-hidden');
            
            // Rotate arrow
            const arrow = btn.querySelector('svg path');
            if (panel.classList.contains('hidden-panel')) {
                arrow.setAttribute('d', 'M15 19l-7-7 7-7');
            } else {
                arrow.setAttribute('d', 'M9 5l7 7-7 7');
            }
        });
        
        // Mode switching
        function switchMode(mode) {
            currentMode = mode;
            
            // Update button styles
            document.getElementById('btnPoint').className = mode === 'point' 
                ? 'flex-1 px-4 py-2 rounded bg-golden text-dark-gray font-semibold'
                : 'flex-1 px-4 py-2 rounded bg-gray-700 text-white font-semibold';
            document.getElementById('btnRoute').className = mode === 'route'
                ? 'flex-1 px-4 py-2 rounded bg-golden text-dark-gray font-semibold'
                : 'flex-1 px-4 py-2 rounded bg-gray-700 text-white font-semibold';
            document.getElementById('btnArea').className = mode === 'area'
                ? 'flex-1 px-4 py-2 rounded bg-golden text-dark-gray font-semibold'
                : 'flex-1 px-4 py-2 rounded bg-gray-700 text-white font-semibold';
            
            // Show/hide filter sections
            document.getElementById('pointFilters').classList.toggle('hidden', mode !== 'point');
            document.getElementById('routeFilters').classList.toggle('hidden', mode !== 'route');
            document.getElementById('areaFilters').classList.toggle('hidden', mode !== 'area');
            
            // Clear layers
            hotelsLayer.clearLayers();
            routesLayer.clearLayers();
            areasLayer.clearLayers();
            
            if (mode === 'point') {
                loadHotels();
            } else if (mode === 'route') {
                loadHotelsForRoute();
            } else if (mode === 'area') {
                
                 loadAreasWithHotels();
              
            }
        }
        
        document.getElementById('btnPoint').addEventListener('click', () => switchMode('point'));
        document.getElementById('btnRoute').addEventListener('click', () => switchMode('route'));
        document.getElementById('btnArea').addEventListener('click', () => switchMode('area'));
        
        // Load hotels
        async function loadHotels() {
            try {
                const response = await fetch('/api/hotels');
                const data = await response.json();
                
                allHotels = data.features;
                
                hotelsLayer.clearLayers();
                
                data.features.forEach(feature => {
                    const { coordinates } = feature.geometry;
                    const props = feature.properties;
                    
                    const fallbackImg = "{{ asset('storage/jumbotron.png') }}";
const storageBase = "{{ asset('storage') }}/";

let imageUrl = fallbackImg;

if (props.image) {
  imageUrl = (props.image.startsWith('http://') || props.image.startsWith('https://'))
    ? props.image
    : storageBase + props.image;
}

                    
                    const marker = L.marker([coordinates[1], coordinates[0]], { icon: customIcon })
                        .bindPopup(`
                            <div class="p-2" style="min-width: 250px;">
                                <img src="${imageUrl}" alt="${props.name}" class="w-full h-32 object-cover rounded mb-2" onerror="this.src='{{ asset('storage/jumbotron.png') }}'">
                                <h3 class="font-bold text-lg mb-1">${props.name}</h3>
                                <p class="text-sm text-gray-600 mb-1">${props.kecamatan}</p>
                                <p class="text-sm font-semibold mb-1">
                                    ${'⭐'.repeat(props.stars)}
                                </p>
                                <p class="text-sm mb-2">
                                    Rp${props.price_min.toLocaleString('id-ID')} - Rp${props.price_max.toLocaleString('id-ID')}
                                </p>
                                <a href="/hotels/${props.id}" class="block w-full text-center bg-golden text-dark-gray px-4 py-2 rounded font-semibold hover:bg-opacity-90 transition">
                                    View Details
                                </a>
                            </div>
                        `, { maxWidth: 280 });
                    
                    hotelsLayer.addLayer(marker);
                });
            } catch (error) {
                console.error('Error loading hotels:', error);
            }
        }
        
        // Load hotels for route mode
        async function loadHotelsForRoute() {
            try {
                const response = await fetch('/api/hotels');
                const data = await response.json();
                
                hotelsLayer.clearLayers();
                
                data.features.forEach(feature => {
                    const { coordinates } = feature.geometry;
                    const props = feature.properties;
                    
                    const marker = L.marker([coordinates[1], coordinates[0]], { icon: customIcon })
                        .on('click', () => selectRoutePoint(props.id, props.name, [coordinates[1], coordinates[0]]));
                    
                    hotelsLayer.addLayer(marker);
                });
            } catch (error) {
                console.error('Error loading hotels:', error);
            }
        }
        
        // Select route point
        function selectRoutePoint(id, name, coords) {
            if (!routePoints.start) {
                routePoints.start = { id, name, coords };
                document.getElementById('startPoint').value = name;
            } else if (!routePoints.end) {
                routePoints.end = { id, name, coords };
                document.getElementById('endPoint').value = name;
            }
        }
        
        // Find route
        document.getElementById('findRoute').addEventListener('click', async () => {
            if (!routePoints.start || !routePoints.end) {
                alert('Please select both start and end points');
                return;
            }
            
            try {
                const response = await fetch('/api/find-route', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        start_id: routePoints.start.id,
                        end_id: routePoints.end.id
                    })
                });
                const data = await response.json();
                
                if (data.route && data.route !== null) {
                    // Display route from database
                    routesLayer.clearLayers();
                    
                    // geometry could be: 
                    // 1. Direct array: [[lng,lat],...]
                    // 2. GeoJSON object: {type: "LineString", coordinates: [[lng,lat],...]}
                    let coordinates;
                    if (Array.isArray(data.route.geometry)) {
                        // Direct array format
                        coordinates = data.route.geometry;
                    } else if (data.route.geometry.coordinates) {
                        // GeoJSON format
                        coordinates = data.route.geometry.coordinates;
                    } else {
                        console.error('Invalid geometry:', data.route.geometry);
                        alert('Invalid route geometry format');
                        return;
                    }
                    
                    // Swap coordinates from [lng, lat] to [lat, lng] for Leaflet
                    const latLngs = coordinates.map(c => [c[1], c[0]]);
                    const polyline = L.polyline(latLngs, {
                        color: '#10B981',
                        weight: 5,
                        opacity: 0.8
                    }).bindPopup(`
                        <div class="p-2">
                            <p class="font-bold">${data.route.properties.name}</p>
                            <p class="text-sm">📏 ${data.route.properties.distance_km} km</p>
                            <p class="text-xs text-gray-500 mt-1">✓ Route from database</p>
                        </div>
                    `);
                    
                    routesLayer.addLayer(polyline);
                    map.fitBounds(polyline.getBounds(), { padding: [50, 50] });
                    
                    document.getElementById('routeDistance').classList.remove('hidden');
                    document.getElementById('distanceValue').textContent = data.route.properties.distance_km;
                } else if (data.error) {
                    // Route not in database - draw straight line fallback
                    routesLayer.clearLayers();
                    
                    const start = routePoints.start.coords;
                    const end = routePoints.end.coords;
                    
                    // Calculate distance using Haversine formula
                    const R = 6371;
                    const dLat = (end[0] - start[0]) * Math.PI / 180;
                    const dLon = (end[1] - start[1]) * Math.PI / 180;
                    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                            Math.cos(start[0] * Math.PI / 180) * Math.cos(end[0] * Math.PI / 180) *
                            Math.sin(dLon/2) * Math.sin(dLon/2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                    const distance = (R * c).toFixed(2);
                    
                    // Draw dashed line
                    const polyline = L.polyline([start, end], {
                        color: '#F59E0B',
                        weight: 4,
                        opacity: 0.7,
                        dashArray: '10, 10'
                    }).bindPopup(`
                        <div class="p-2">
                            <p class="font-bold">Direct Route (Not in DB)</p>
                            <p class="text-sm">📏 ${distance} km (straight line)</p>
                            <p class="text-sm">From: ${routePoints.start.name}</p>
                            <p class="text-sm">To: ${routePoints.end.name}</p>
                            <p class="text-xs text-orange-600 mt-1">⚠ Route not available in database</p>
                        </div>
                    `);
                    
                    routesLayer.addLayer(polyline);
                    map.fitBounds(polyline.getBounds(), { padding: [50, 50] });
                    
                    document.getElementById('routeDistance').classList.remove('hidden');
                    document.getElementById('distanceValue').textContent = distance;
                }
            } catch (error) {
                console.error('Error finding route:', error);
                alert('Error finding route. Please try again.');
            }
        });
        
        // Clear route
        document.getElementById('clearRoute').addEventListener('click', () => {
            routePoints = { start: null, end: null };
            document.getElementById('startPoint').value = '';
            document.getElementById('endPoint').value = '';
            document.getElementById('routeDistance').classList.add('hidden');
            routesLayer.clearLayers();
        });
        
async function loadAreasWithHotels() {
    try {
        // Get selected wilayah filters
        const selectedWilayah = Array.from(document.querySelectorAll('.wilayah-filter:checked'))
            .map(cb => cb.value);
        
        // Build query string for areas
        let areaUrl = '/api/kecamatan';
        if (selectedWilayah.length > 0) {
            const params = new URLSearchParams();
            selectedWilayah.forEach(w => params.append('wilayah[]', w));
            areaUrl += '?' + params.toString();
        }
        
        // Build query string for hotels
        let hotelUrl = '/api/hotels';
        if (selectedWilayah.length > 0) {
            const params = new URLSearchParams();
            selectedWilayah.forEach(w => params.append('wilayah[]', w));
            hotelUrl += '?' + params.toString();
        }
        
        // Fetch both areas and hotels
        const [areaResponse, hotelResponse] = await Promise.all([
            fetch(areaUrl),
            fetch(hotelUrl)
        ]);
        
        const areaData = await areaResponse.json();
        const hotelData = await hotelResponse.json();
        
        console.log('Areas loaded:', areaData.features?.length);
        console.log('Hotels loaded:', hotelData.features?.length);
        
        // Clear both layers
        areasLayer.clearLayers();
        hotelsLayer.clearLayers();
        
        // Add areas (polygons)
        if (areaData.features && areaData.features.length > 0) {
            const geoJsonLayer = L.geoJSON(areaData, {
                style: (feature) => {
                    // Different colors per wilayah
                    const colors = {
                        'Surabaya Tengah': { border: '#FF6B35', fill: '#FFE66D' },
                        'Surabaya Barat': { border: '#4ECDC4', fill: '#95E1D3' },
                        'Surabaya Timur': { border: '#F38181', fill: '#FFEAA7' },
                        'Surabaya Utara': { border: '#AA96DA', fill: '#FCBAD3' },
                        'Surabaya Selatan': { border: '#74B9FF', fill: '#A29BFE' }
                    };
                    
                    const wilayah = feature.properties.wilayah;
                    const colorScheme = colors[wilayah] || { border: '#FF6B35', fill: '#FFE66D' };
                    
                    return {
                        color: colorScheme.border,
                        weight: 3,
                        fillColor: colorScheme.fill,
                        fillOpacity: 0.3
                    };
                },
                onEachFeature: (feature, layer) => {
                    const name = feature.properties.name || 'Unknown';
                    const wilayah = feature.properties.wilayah || '';
                    layer.bindPopup(`
                        <div class="p-2">
                            <strong>${name}</strong>
                            <p class="text-sm text-gray-600">${wilayah}</p>
                        </div>
                    `);
                }
            });
            
            geoJsonLayer.addTo(areasLayer);
            
            // Fit map to areas
            if (selectedWilayah.length > 0) {
                map.fitBounds(geoJsonLayer.getBounds());
            }
        }
        
        // Add hotels (markers)
        if (hotelData.features && hotelData.features.length > 0) {
            hotelData.features.forEach(feature => {
                const { coordinates } = feature.geometry;
                const props = feature.properties;
                
                const fallbackImg = "{{ asset('storage/jumbotron.png') }}";
const storageBase = "{{ asset('storage') }}/";

let imageUrl = fallbackImg;

if (props.image) {
  imageUrl = (props.image.startsWith('http://') || props.image.startsWith('https://'))
    ? props.image
    : storageBase + props.image;
}

                const marker = L.marker([coordinates[1], coordinates[0]], { icon: customIcon })
                    .bindPopup(`
                        <div class="p-2" style="min-width: 250px;">
                            <img src="${imageUrl}" alt="${props.name}" class="w-full h-32 object-cover rounded mb-2" onerror="this.src='{{ asset('storage/jumbotron.png') }}'">
                            <h3 class="font-bold text-lg mb-1">${props.name}</h3>
                            <p class="text-sm text-gray-600 mb-1">${props.kecamatan}</p>
                            <p class="text-sm font-semibold mb-1">
                                ${'⭐'.repeat(props.stars)}
                            </p>
                            <p class="text-sm mb-2">
                                Rp${props.price_min.toLocaleString('id-ID')} - Rp${props.price_max.toLocaleString('id-ID')}
                            </p>
                            <a href="/hotels/${props.id}" class="block w-full text-center bg-golden text-dark-gray px-4 py-2 rounded font-semibold hover:bg-opacity-90 transition">
                                View Details
                            </a>
                        </div>
                    `, { maxWidth: 280 });
                
                hotelsLayer.addLayer(marker);
            });
            
            console.log(`${areaData.features.length} areas and ${hotelData.features.length} hotels displayed`);
        }
        
    } catch (error) {
        console.error('Error loading areas and hotels:', error);
        alert('Error loading map data: ' + error.message);
    }
}
async function loadAreas() {
    
    try {
        // Get selected wilayah filters
        const selectedWilayah = Array.from(document.querySelectorAll('.wilayah-filter:checked'))
            .map(cb => cb.value);
        
        // Build query string
        let url = '/api/kecamatan';
        if (selectedWilayah.length > 0) {
            // Send as array parameter
            const params = new URLSearchParams();
            selectedWilayah.forEach(w => params.append('wilayah[]', w));
            url += '?' + params.toString();
        }
        
        const response = await fetch(url);
        const data = await response.json();
        
      
        
        areasLayer.clearLayers();
        
        // Check if there are features
        if (data.features && data.features.length > 0) {
            L.geoJSON(data, {
                style: { 
                        color: '#FF6B35',         
                        weight: 3,                 
                        fillColor: '#FFE66D',     
                        fillOpacity: 0.4           
                    },
                onEachFeature: (feature, layer) => {
                    const name = feature.properties.name || 'Unknown';
                    const wilayah = feature.properties.wilayah || '';
                    layer.bindPopup(`
                        <div class="p-2">
                            <strong>${name}</strong>
                            <p class="text-sm text-gray-600">${wilayah}</p>
                        </div>
                    `);
                }
            }).addTo(areasLayer);
            
            console.log(`${data.features.length} areas displayed`);
        } else {
            console.log('No areas to display');
        }
    } catch (error) {
        console.error('Error loading areas:', error);
        alert('Error loading areas. Please check console for details.');
    }
}
        
        // Price range sliders
        document.getElementById('priceMin').addEventListener('input', function(e) {
            const value = parseInt(e.target.value);
            document.getElementById('priceMinValue').textContent = 'Rp ' + value.toLocaleString('id-ID') + ',00';
            applyFilters();
        });
        
        document.getElementById('priceMax').addEventListener('input', function(e) {
            const value = parseInt(e.target.value);
            document.getElementById('priceMaxValue').textContent = 'Rp ' + value.toLocaleString('id-ID') + ',00';
            applyFilters();
        });
        
        // Apply filters
        function applyFilters() {
            const minPrice = parseInt(document.getElementById('priceMin').value);
            const maxPrice = parseInt(document.getElementById('priceMax').value);
            const selectedRatings = Array.from(document.querySelectorAll('.rating-filter:checked')).map(cb => parseInt(cb.value));
            const selectedAreas = Array.from(document.querySelectorAll('.area-filter:checked')).map(cb => cb.value);
            
            hotelsLayer.clearLayers();
            
            allHotels.forEach(feature => {
                const { coordinates } = feature.geometry;
                const props = feature.properties;
                
                // Apply filters
                if (props.price_min < minPrice || props.price_max > maxPrice) return;
                if (selectedRatings.length > 0 && !selectedRatings.includes(props.stars)) return;
                if (selectedAreas.length > 0 && !selectedAreas.includes(props.wilayah)) return;
                
                const fallbackImg = "{{ asset('storage/jumbotron.png') }}";
const storageBase = "{{ asset('storage') }}/";

let imageUrl = fallbackImg;

if (props.image) {
  imageUrl = (props.image.startsWith('http://') || props.image.startsWith('https://'))
    ? props.image
    : storageBase + props.image;
}

                
                const marker = L.marker([coordinates[1], coordinates[0]], { icon: customIcon })
                    .bindPopup(`
                        <div class="p-2" style="min-width: 250px;">
                            <img src="${imageUrl}" alt="${props.name}" class="w-full h-32 object-cover rounded mb-2" onerror="this.src='{{ asset('storage/jumbotron.png') }}'">
                            <h3 class="font-bold text-lg mb-1">${props.name}</h3>
                            <p class="text-sm text-gray-600 mb-1">${props.kecamatan}</p>
                            <p class="text-sm font-semibold mb-1">
                                ${'⭐'.repeat(props.stars)}
                            </p>
                            <p class="text-sm mb-2">
                                Rp${props.price_min.toLocaleString('id-ID')} - Rp${props.price_max.toLocaleString('id-ID')}
                            </p>
                            <a href="/hotels/${props.id}" class="block w-full text-center bg-golden text-dark-gray px-4 py-2 rounded font-semibold hover:bg-opacity-90 transition">
                                View Details
                            </a>
                        </div>
                    `, { maxWidth: 280 });
                
                hotelsLayer.addLayer(marker);
            });
        }
        
        // Filter change listeners
        document.querySelectorAll('.rating-filter, .area-filter').forEach(checkbox => {
            checkbox.addEventListener('change', applyFilters);
        });
        
        // Wilayah filter for area mode
        document.querySelectorAll('.wilayah-filter').forEach(checkbox => {
            checkbox.addEventListener('change', loadAreasWithHotels); // Use combined function
        });
        
        // Initial load
        loadHotels();
    </script>
    @endpush
</x-app-layout>
