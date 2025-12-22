<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HISurabaya - Map</title>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        #map {
            height: 100vh;
            width: 100%;
        }
        
        .leaflet-popup-content {
            min-width: 250px;
        }
        
        .filter-panel {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            max-width: 350px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        @media (max-width: 768px) {
            .filter-panel {
                width: 90%;
                right: 5%;
            }
        }
    </style>
</head>
<body>
    <!-- Filter Panel -->
    <div x-data="mapFilters()" x-init="loadHotels()" class="filter-panel">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Filter Hotels</h2>
            <button @click="togglePanel()" class="text-gray-500 hover:text-gray-700">
                <span x-show="panelOpen">✕</span>
                <span x-show="!panelOpen">☰</span>
            </button>
        </div>
        
        <div x-show="panelOpen" x-transition>
            <!-- Map Mode -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Map Mode</label>
                <select x-model="mode" @change="changeMode()" class="w-full border rounded px-3 py-2">
                    <option value="point">Point Mode (Hotels)</option>
                    <option value="route">Route Mode</option>
                    <option value="area">Area Mode (Kecamatan)</option>
                </select>
            </div>
            
            <!-- Point Mode Filters -->
            <div x-show="mode === 'point'" class="space-y-4">
                <!-- Name Search -->
                <div>
                    <label class="block text-sm font-medium mb-2">Search by Name</label>
                    <input 
                        type="text" 
                        x-model="filters.name" 
                        placeholder="Hotel name..."
                        class="w-full border rounded px-3 py-2"
                    >
                </div>
                
                <!-- Wilayah -->
                <div>
                    <label class="block text-sm font-medium mb-2">Wilayah</label>
                    <select x-model="filters.wilayah" class="w-full border rounded px-3 py-2">
                        <option value="">All Wilayah</option>
                        <option value="Surabaya Barat">Surabaya Barat</option>
                        <option value="Surabaya Timur">Surabaya Timur</option>
                        <option value="Surabaya Utara">Surabaya Utara</option>
                        <option value="Surabaya Selatan">Surabaya Selatan</option>
                        <option value="Surabaya Tengah">Surabaya Tengah</option>
                    </select>
                </div>
                
                <!-- Star Class -->
                <div>
                    <label class="block text-sm font-medium mb-2">Star Rating</label>
                    <select x-model="filters.stars" class="w-full border rounded px-3 py-2">
                        <option value="">All Stars</option>
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="2">2 Stars</option>
                        <option value="1">1 Star</option>
                    </select>
                </div>
                
                <!-- Price Range -->
                <div>
                    <label class="block text-sm font-medium mb-2">Price Range (IDR)</label>
                    <input 
                        type="number" 
                        x-model="filters.price_min" 
                        placeholder="Min price"
                        class="w-full border rounded px-3 py-2 mb-2"
                    >
                    <input 
                        type="number" 
                        x-model="filters.price_max" 
                        placeholder="Max price"
                        class="w-full border rounded px-3 py-2"
                    >
                </div>
                
                <!-- Apply Button -->
                <button 
                    @click="applyFilters()" 
                    class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                >
                    Apply Filters
                </button>
                
                <!-- Reset Button -->
                <button 
                    @click="resetFilters()" 
                    class="w-full bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300"
                >
                    Reset
                </button>
            </div>
            
            <!-- Route Mode -->
            <div x-show="mode === 'route'" class="space-y-4">
                <!-- Starting Point -->
                <div>
                    <label class="block text-sm font-medium mb-2">
                        <span class="inline-block w-6 h-6 border-2 border-gray-600 rounded-full mr-2"></span>
                        Starting Point
                    </label>
                    <input 
                        type="text" 
                        x-model="routePoints.start.name"
                        @click="activeRouteField = 'start'"
                        placeholder="Choose starting point or click on the maps"
                        :class="activeRouteField === 'start' ? 'ring-2 ring-blue-500' : ''"
                        class="w-full border rounded px-3 py-2 bg-gray-50 cursor-pointer"
                        readonly
                    >
                </div>
                
                <!-- Destination Point -->
                <div>
                    <label class="block text-sm font-medium mb-2">
                        <span class="inline-block w-6 h-6 bg-red-500 rounded-full mr-2"></span>
                        Destination Point
                    </label>
                    <input 
                        type="text" 
                        x-model="routePoints.end.name"
                        @click="activeRouteField = 'end'"
                        placeholder="Choose destination point or click on the maps"
                        :class="activeRouteField === 'end' ? 'ring-2 ring-blue-500' : ''"
                        class="w-full border rounded px-3 py-2 bg-gray-50 cursor-pointer"
                        readonly
                    >
                </div>
                
                <!-- Mileage Display -->
                <div x-show="routeDistance" class="p-3 bg-blue-50 rounded">
                    <p class="text-lg font-bold">
                        Mileage: <span x-text="routeDistance"></span>km
                    </p>
                </div>
                
                <!-- Find Route Button -->
                <button 
                    @click="findRoute()" 
                    x-show="routePoints.start.id && routePoints.end.id"
                    class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                >
                    Find Route
                </button>
                
                <!-- Clear Button -->
                <button 
                    @click="clearRouteSelection()" 
                    class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                >
                    Clear Selection
                </button>
                
                <p class="text-xs text-gray-500 text-center">Click on the input field above, then click on a hotel marker to set that point</p>
            </div>
            
            <!-- Area Mode -->
            <div x-show="mode === 'area'" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Show Wilayah</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" value="Surabaya Barat" x-model="selectedWilayah" @change="applyAreaFilters()" class="rounded mr-2">
                            <span class="text-sm">Surabaya Barat</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" value="Surabaya Timur" x-model="selectedWilayah" @change="applyAreaFilters()" class="rounded mr-2">
                            <span class="text-sm">Surabaya Timur</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" value="Surabaya Utara" x-model="selectedWilayah" @change="applyAreaFilters()" class="rounded mr-2">
                            <span class="text-sm">Surabaya Utara</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" value="Surabaya Selatan" x-model="selectedWilayah" @change="applyAreaFilters()" class="rounded mr-2">
                            <span class="text-sm">Surabaya Selatan</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" value="Surabaya Tengah" x-model="selectedWilayah" @change="applyAreaFilters()" class="rounded mr-2">
                            <span class="text-sm">Surabaya Tengah</span>
                        </label>
                    </div>
                </div>
                
                <!-- Kecamatan Filter -->
                <div x-show="selectedWilayah.length > 0">
                    <label class="block text-sm font-medium mb-2">Filter by Kecamatan</label>
                    <div class="space-y-1 max-h-48 overflow-y-auto border rounded p-2">
                        <template x-for="kec in allKecamatan" :key="kec.id">
                            <label class="flex items-center">
                                <input type="checkbox" :value="kec.name" x-model="selectedKecamatan" @change="applyAreaFilters()" class="rounded mr-2">
                                <span class="text-xs" x-text="kec.name"></span>
                            </label>
                        </template>
                    </div>
                    <button @click="selectedKecamatan = []; applyAreaFilters()" class="text-xs text-blue-600 hover:underline mt-1">Clear kecamatan</button>
                </div>
                
                <!-- Hotel Count -->
                <div class="p-3 bg-gray-100 rounded">
                    <p class="text-sm font-semibold">
                        <span x-text="hotelCount"></span> hotels found
                    </p>
                </div>
                
                <p class="text-xs text-gray-500">Note: Only some kecamatan have boundary polygons displayed</p>
            </div>
        </div>
    </div>
    
    <!-- Hotel Detail Modal -->
    <div id="hotelModal" class="hidden fixed inset-0 z-[2000] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeHotelModal()"></div>
            
            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <!-- Close button -->
                    <button onclick="closeHotelModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 z-10">
                        <span class="text-2xl">&times;</span>
                    </button>
                    
                    <!-- Image Carousel -->
                    <div class="relative mb-4">
                        <div id="carouselImages" class="relative w-full aspect-video overflow-hidden rounded-lg bg-gray-200">
                            <!-- Images will be inserted here -->
                        </div>
                        
                        <!-- Carousel controls -->
                        <button onclick="previousImage()" class="absolute top-1/2 left-2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button onclick="nextImage()" class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        
                        <!-- Image counter -->
                        <div id="imageCounter" class="absolute bottom-2 right-2 bg-black bg-opacity-50 text-white px-3 py-1 rounded text-sm"></div>
                    </div>
                    
                    <!-- Hotel Info -->
                    <div id="modalHotelInfo">
                        <!-- Hotel details will be inserted here -->
                    </div>
                </div>
                
                <!-- Modal footer -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <a id="viewDetailsBtn" href="#" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        View Full Details
                    </a>
                    <button type="button" onclick="closeHotelModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Map Container -->
    <div id="map"></div>
    
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
        
        // Layer groups
        let hotelsLayer = L.layerGroup().addTo(map);
        let routesLayer = L.layerGroup().addTo(map);
        let areasLayer = L.layerGroup().addTo(map);
        
        // Modal and carousel state
        let currentHotelData = null;
        let currentImageIndex = 0;
        let hotelImages = [];
        
        // Modal control functions
        function openHotelModal(hotelData) {
            currentHotelData = hotelData;
            hotelImages = hotelData.images || [];
            currentImageIndex = 0;
            
            // Set up images
            const carouselContainer = document.getElementById('carouselImages');
            carouselContainer.innerHTML = '';
            
            if (hotelImages.length > 0) {
                hotelImages.forEach((imageUrl, index) => {
                    const imgSrc = imageUrl.startsWith('http') ? imageUrl : `/storage/${imageUrl}`;
                    const imgDiv = document.createElement('div');
                    imgDiv.className = index === 0 ? 'carousel-item active w-full aspect-video' : 'carousel-item hidden w-full aspect-video';
                    imgDiv.innerHTML = `<img src="${imgSrc}" alt="${hotelData.name}" class="w-full h-full object-cover">`;
                    carouselContainer.appendChild(imgDiv);
                });
                updateImageCounter();
            } else {
                carouselContainer.innerHTML = '<div class="w-full aspect-video bg-gray-200 flex items-center justify-center text-gray-500">No images available</div>';
            }
            
            // Set hotel info
            const stars = '⭐'.repeat(hotelData.stars);
            const priceMin = new Intl.NumberFormat('id-ID').format(hotelData.price_min);
            const priceMax = new Intl.NumberFormat('id-ID').format(hotelData.price_max);
            
            document.getElementById('modalHotelInfo').innerHTML = `
                <h3 class="text-xl font-bold mb-2">${hotelData.name}</h3>
                <div class="flex items-center mb-2">
                    <span class="text-yellow-400 mr-2">${stars}</span>
                    <span class="text-gray-600">(${hotelData.stars} Star)</span>
                </div>
                <p class="text-gray-700 mb-2">${hotelData.description || ''}</p>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>
                        <span class="font-semibold">Address:</span><br>
                        <span class="text-gray-600">${hotelData.address}</span>
                    </div>
                    <div>
                        <span class="font-semibold">Kecamatan:</span><br>
                        <span class="text-gray-600">${hotelData.kecamatan} (${hotelData.wilayah})</span>
                    </div>
                    <div class="col-span-2">
                        <span class="font-semibold">Price Range:</span><br>
                        <span class="text-blue-600 font-bold">Rp ${priceMin} - Rp ${priceMax}</span>
                    </div>
                </div>
            `;
            
            // Set view details button link
            document.getElementById('viewDetailsBtn').href = `/hotels/${hotelData.id}`;
            
            // Show modal
            document.getElementById('hotelModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeHotelModal() {
            document.getElementById('hotelModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentHotelData = null;
            hotelImages = [];
            currentImageIndex = 0;
        }
        
        function nextImage() {
            if (hotelImages.length === 0) return;
            
            document.querySelectorAll('.carousel-item')[currentImageIndex].classList.add('hidden');
            document.querySelectorAll('.carousel-item')[currentImageIndex].classList.remove('active');
            
            currentImageIndex = (currentImageIndex + 1) % hotelImages.length;
            
            document.querySelectorAll('.carousel-item')[currentImageIndex].classList.remove('hidden');
            document.querySelectorAll('.carousel-item')[currentImageIndex].classList.add('active');
            
            updateImageCounter();
        }
        
        function previousImage() {
            if (hotelImages.length === 0) return;
            
            document.querySelectorAll('.carousel-item')[currentImageIndex].classList.add('hidden');
            document.querySelectorAll('.carousel-item')[currentImageIndex].classList.remove('active');
            
            currentImageIndex = (currentImageIndex - 1 + hotelImages.length) % hotelImages.length;
            
            document.querySelectorAll('.carousel-item')[currentImageIndex].classList.remove('hidden');
            document.querySelectorAll('.carousel-item')[currentImageIndex].classList.add('active');
            
            updateImageCounter();
        }
        
        function updateImageCounter() {
            if (hotelImages.length > 0) {
                document.getElementById('imageCounter').textContent = `${currentImageIndex + 1} / ${hotelImages.length}`;
            }
        }
        
        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeHotelModal();
            }
        });
        
        // Alpine.js component
        function mapFilters() {
            return {
                mode: 'point',
                panelOpen: true,
                filters: {
                    name: '',
                    wilayah: '',
                    stars: '',
                    price_min: '',
                    price_max: ''
                },
                routePoints: {
                    start: { id: null, name: '', coords: null },
                    end: { id: null, name: '', coords: null }
                },
                routeDistance: null,
                isRouteMode: false,
                activeRouteField: 'start', // Track which field is active: 'start' or 'end'
                selectedWilayah: [],
                selectedKecamatan: [],
                allKecamatan: [],
                hotelCount: 0,
                
                togglePanel() {
                    this.panelOpen = !this.panelOpen;
                },
                
                changeMode() {
                    // Clear all layers
                    hotelsLayer.clearLayers();
                    routesLayer.clearLayers();
                    areasLayer.clearLayers();
                    
                    // Reset route selection
                    this.clearRouteSelection();
                    
                    if (this.mode === 'point') {
                        this.isRouteMode = false;
                        this.loadHotels();
                    } else if (this.mode === 'route') {
                        this.isRouteMode = true;
                        this.loadHotelsForRouteMode();
                    } else if (this.mode === 'area') {
                        this.isRouteMode = false;
                        this.selectedWilayah = [];
                        this.selectedKecamatan = [];
                        this.allKecamatan = [];
                        this.loadAreas();
                        this.loadHotelsForAreaMode();
                    }
                },
                
                async loadHotels() {
                    const params = new URLSearchParams();
                    if (this.filters.name) params.append('name', this.filters.name);
                    if (this.filters.wilayah) params.append('wilayah', this.filters.wilayah);
                    if (this.filters.stars) params.append('stars', this.filters.stars);
                    if (this.filters.price_min) params.append('price_min', this.filters.price_min);
                    if (this.filters.price_max) params.append('price_max', this.filters.price_max);
                    
                    const response = await fetch(`/api/hotels?${params}`);
                    const geojson = await response.json();
                    
                    hotelsLayer.clearLayers();
                    this.hotelCount = geojson.features.length;
                    
                    geojson.features.forEach(feature => {
                        const coords = feature.geometry.coordinates;
                        const props = feature.properties;
                        
                        const marker = L.marker([coords[1], coords[0]]);
                        
                        // Open modal on click
                        marker.on('click', () => {
                            openHotelModal(props);
                        });
                        
                        hotelsLayer.addLayer(marker);
                    });
                },
                
                async loadHotelsForRouteMode() {
                    const response = await fetch('/api/hotels');
                    const geojson = await response.json();
                    
                    hotelsLayer.clearLayers();
                    
                    geojson.features.forEach(feature => {
                        const coords = feature.geometry.coordinates;
                        const props = feature.properties;
                        
                        const marker = L.marker([coords[1], coords[0]])
                            .bindPopup(`
                                <div class="p-2">
                                    <h3 class="font-bold text-lg">${props.name}</h3>
                                    <p class="text-sm">⭐ ${props.stars} Star</p>
                                    <p class="text-sm">Click to select as route point</p>
                                </div>
                            `);
                        
                        // Add click handler for route selection
                        marker.on('click', () => {
                            this.selectRoutePoint(props.id, props.name, [coords[1], coords[0]]);
                        });
                        
                        hotelsLayer.addLayer(marker);
                    });
                    
                    // Enable map click for coordinate selection (snap to nearest hotel)
                    const self = this;
                    map.off('click'); // Remove any existing listeners
                    map.on('click', async (e) => {
                        console.log('Map clicked, isRouteMode:', self.isRouteMode);
                        if (self.isRouteMode) {
                            const lat = e.latlng.lat;
                            const lng = e.latlng.lng;
                            console.log('Searching nearest hotel at:', lat, lng);
                            await self.selectNearestHotel(lat, lng);
                        }
                    });
                },
                
                async selectNearestHotel(lat, lng) {
                    try {
                        console.log('Fetching nearest hotel...');
                        const response = await fetch(`/api/radius-search?lat=${lat}&lng=${lng}&radius=100`);
                        
                        if (!response.ok) {
                            console.error('Radius search failed:', response.status);
                            alert('Error finding nearby hotels. Please try clicking on a marker directly.');
                            return;
                        }
                        
                        const geojson = await response.json();
                        console.log('Radius search result:', geojson);
                        
                        if (geojson.features && geojson.features.length > 0) {
                            // Get nearest hotel (first result)
                            const nearest = geojson.features[0];
                            const props = nearest.properties;
                            const coords = nearest.geometry.coordinates;
                            
                            console.log('Nearest hotel found:', props.name);
                            this.selectRoutePoint(props.id, props.name, [coords[1], coords[0]]);
                        } else {
                            alert('No hotels found nearby. Please click closer to a hotel marker.');
                        }
                    } catch (error) {
                        console.error('Error finding nearest hotel:', error);
                        alert('Error finding nearby hotels. Please try clicking on a marker directly.');
                    }
                },
                
                async loadRoutes() {
                    try {
                        const response = await fetch('/api/routes');
                        const geojson = await response.json();
                        
                        console.log('Routes data:', geojson);
                        
                        routesLayer.clearLayers();
                        
                        if (!geojson.features || geojson.features.length === 0) {
                            console.warn('No routes found');
                            return;
                        }
                        
                        geojson.features.forEach(feature => {
                            if (!feature.geometry || !feature.geometry.coordinates) {
                                console.warn('Invalid route geometry:', feature);
                                return;
                            }
                            
                            // Coordinates are already [lng, lat], convert to [lat, lng] for Leaflet
                            const coords = feature.geometry.coordinates.map(c => [c[1], c[0]]);
                            const props = feature.properties;
                            
                            const polyline = L.polyline(coords, {
                                color: '#3B82F6',
                                weight: 4,
                                opacity: 0.7
                            }).bindPopup(`
                                <div class="p-2">
                                    <p class="font-bold">${props.name}</p>
                                    <p class="text-sm">📏 ${props.distance_km} km</p>
                                    <p class="text-sm">From: ${props.start_hotel}</p>
                                    <p class="text-sm">To: ${props.end_hotel}</p>
                                </div>
                            `);
                            
                            routesLayer.addLayer(polyline);
                        });
                        
                        console.log('Loaded', geojson.features.length, 'routes');
                    } catch (error) {
                        console.error('Error loading routes:', error);
                    }
                },
                
                async loadAreas() {
                    const params = new URLSearchParams();
                    if (this.filters.wilayah) params.append('wilayah', this.filters.wilayah);
                    
                    const response = await fetch(`/api/kecamatan?${params}`);
                    const geojson = await response.json();
                    
                    areasLayer.clearLayers();
                    
                    const colors = {
                        'Surabaya Barat': '#FF6B6B',
                        'Surabaya Timur': '#4ECDC4',
                        'Surabaya Utara': '#45B7D1',
                        'Surabaya Selatan': '#96CEB4',
                        'Surabaya Tengah': '#FFEAA7'
                    };
                    
                    geojson.features.forEach(feature => {
                        if (feature.geometry && feature.geometry.coordinates) {
                            const coords = feature.geometry.coordinates[0].map(c => [c[1], c[0]]);
                            const props = feature.properties;
                            
                            const polygon = L.polygon(coords, {
                                color: colors[props.wilayah] || '#999',
                                fillColor: colors[props.wilayah] || '#999',
                                fillOpacity: 0.3
                            }).bindPopup(`
                                <div class="p-2">
                                    <h3 class="font-bold">${props.name}</h3>
                                    <p class="text-sm">${props.wilayah}</p>
                                </div>
                            `);
                            
                            areasLayer.addLayer(polygon);
                        }
                    });
                },
                
                enableRouteMode() {
                    // Route mode implementation
                    console.log('Route mode enabled - click hotel markers to select route points');
                },
                
                selectRoutePoint(id, name, coords) {
                    if (this.activeRouteField === 'start') {
                        // Update starting point
                        this.routePoints.start = { id, name, coords };
                        console.log('Starting point set to:', name);
                        
                        // If destination is already set, auto-find route
                        if (this.routePoints.end.id) {
                            this.routeDistance = null;
                            routesLayer.clearLayers();
                            this.findRoute();
                        } else {
                            // Auto-switch to destination field
                            this.activeRouteField = 'end';
                        }
                    } else {
                        // Update destination point
                        this.routePoints.end = { id, name, coords };
                        console.log('Destination point set to:', name);
                        
                        // If starting point is already set, auto-find route
                        if (this.routePoints.start.id) {
                            this.routeDistance = null;
                            routesLayer.clearLayers();
                            this.findRoute();
                        }
                    }
                },
                
                async findRoute() {
                    if (!this.routePoints.start.id || !this.routePoints.end.id) {
                        alert('Please select both starting and destination points');
                        return;
                    }
                    
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        
                        const response = await fetch('/api/find-route', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                start_id: this.routePoints.start.id,
                                end_id: this.routePoints.end.id
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (data.route && data.route !== null) {
                            // Display route from database
                            routesLayer.clearLayers();
                            
                            const coords = data.route.geometry.coordinates.map(c => [c[1], c[0]]);
                            const polyline = L.polyline(coords, {
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
                            this.routeDistance = data.route.properties.distance_km;
                        } else if (data.error) {
                            // Route not in database - draw straight line fallback
                            console.warn('Route not in database, drawing straight line');
                            routesLayer.clearLayers();
                            
                            const start = this.routePoints.start.coords;
                            const end = this.routePoints.end.coords;
                            
                            // Calculate distance using Haversine formula
                            const distance = this.calculateDistance(start[0], start[1], end[0], end[1]);
                            
                            // Draw dashed line
                            const polyline = L.polyline([start, end], {
                                color: '#F59E0B',
                                weight: 4,
                                opacity: 0.7,
                                dashArray: '10, 10'
                            }).bindPopup(`
                                <div class="p-2">
                                    <p class="font-bold">Direct Route (Not in DB)</p>
                                    <p class="text-sm">📏 ${distance.toFixed(2)} km (straight line)</p>
                                    <p class="text-sm">From: ${this.routePoints.start.name}</p>
                                    <p class="text-sm">To: ${this.routePoints.end.name}</p>
                                    <p class="text-xs text-orange-600 mt-1">⚠ Route not available in database</p>
                                </div>
                            `);
                            
                            routesLayer.addLayer(polyline);
                            map.fitBounds(polyline.getBounds(), { padding: [50, 50] });
                            this.routeDistance = distance.toFixed(2);
                        }
                    } catch (error) {
                        console.error('Error finding route:', error);
                        alert('Error finding route. Please try again.');
                    }
                },
                
                clearRouteSelection() {
                    this.routePoints = {
                        start: { id: null, name: '', coords: null },
                        end: { id: null, name: '', coords: null }
                    };
                    this.routeDistance = null;
                    this.activeRouteField = 'start';
                    routesLayer.clearLayers();
                },
                
                calculateDistance(lat1, lon1, lat2, lon2) {
                    // Haversine formula for distance calculation
                    const R = 6371; // Earth radius in km
                    const dLat = (lat2 - lat1) * Math.PI / 180;
                    const dLon = (lon2 - lon1) * Math.PI / 180;
                    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                            Math.sin(dLon/2) * Math.sin(dLon/2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                    return R * c;
                },
                
                async applyAreaFilters() {
                    areasLayer.clearLayers();
                    hotelsLayer.clearLayers();
                    
                    // Load kecamatan list for selected wilayah
                    await this.loadKecamatanList();
                    
                    // Load areas for selected wilayah or kecamatan
                    if (this.selectedKecamatan.length > 0) {
                        // Load specific kecamatan
                        for (const kecName of this.selectedKecamatan) {
                            await this.loadAreasByKecamatan(kecName);
                        }
                    } else if (this.selectedWilayah.length > 0) {
                        // Load all areas for selected wilayah
                        for (const wilayah of this.selectedWilayah) {
                            await this.loadAreasForWilayah(wilayah);
                        }
                    }
                    
                    // Load hotels for selected filters
                    await this.loadHotelsForAreaMode();
                },
                
                async loadKecamatanList() {
                    if (this.selectedWilayah.length === 0) {
                        this.allKecamatan = [];
                        return;
                    }
                    
                    let kecamatanList = [];
                    for (const wilayah of this.selectedWilayah) {
                        const response = await fetch(`/api/kecamatan?wilayah=${wilayah}`);
                        const geojson = await response.json();
                        
                        geojson.features.forEach(feature => {
                            kecamatanList.push({
                                id: feature.properties.id,
                                name: feature.properties.name,
                                wilayah: feature.properties.wilayah
                            });
                        });
                    }
                    
                    this.allKecamatan = kecamatanList.sort((a, b) => a.name.localeCompare(b.name));
                },
                
                async loadAreasByKecamatan(kecamatanName) {
                    const response = await fetch('/api/kecamatan');
                    const geojson = await response.json();
                    
                    console.log('Looking for kecamatan:', kecamatanName);
                    
                    const colors = {
                        'Surabaya Barat': '#FF6B6B',
                        'Surabaya Timur': '#4ECDC4',
                        'Surabaya Utara': '#45B7D1',
                        'Surabaya Selatan': '#96CEB4',
                        'Surabaya Tengah': '#FFEAA7'
                    };
                    
                    geojson.features.forEach(feature => {
                        if (feature.properties.name === kecamatanName) {
                            console.log('Found kecamatan:', kecamatanName);
                            console.log('Geometry object:', feature.geometry);
                            console.log('Has coordinates?', !!feature.geometry?.coordinates);
                            
                            if (feature.geometry && feature.geometry.coordinates) {
                                const coords = feature.geometry.coordinates[0].map(c => [c[1], c[0]]);
                                const props = feature.properties;
                                
                                console.log('Rendering polygon for:', props.name, 'with', coords.length, 'points');
                                
                                const polygon = L.polygon(coords, {
                                    color: colors[props.wilayah] || '#999',
                                    fillColor: colors[props.wilayah] || '#999',
                                    fillOpacity: 0.4,
                                    weight: 3
                                }).bindPopup(`
                                    <div class="p-2">
                                        <h3 class="font-bold">${props.name}</h3>
                                        <p class="text-sm">${props.wilayah}</p>
                                    </div>
                                `);
                                
                                areasLayer.addLayer(polygon);
                                console.log('Polygon added to layer for:', props.name);
                            } else {
                                console.warn('Kecamatan', kecamatanName, 'has no polygon data');
                            }
                        }
                    });
                },
                
                async loadAreasForWilayah(wilayah) {
                    const response = await fetch(`/api/kecamatan?wilayah=${wilayah}`);
                    const geojson = await response.json();
                    
                    const colors = {
                        'Surabaya Barat': '#FF6B6B',
                        'Surabaya Timur': '#4ECDC4',
                        'Surabaya Utara': '#45B7D1',
                        'Surabaya Selatan': '#96CEB4',
                        'Surabaya Tengah': '#FFEAA7'
                    };
                    
                    geojson.features.forEach(feature => {
                        if (feature.geometry && feature.geometry.coordinates) {
                            const coords = feature.geometry.coordinates[0].map(c => [c[1], c[0]]);
                            const props = feature.properties;
                            
                            const polygon = L.polygon(coords, {
                                color: colors[props.wilayah] || '#999',
                                fillColor: colors[props.wilayah] || '#999',
                                fillOpacity: 0.3
                            }).bindPopup(`
                                <div class="p-2">
                                    <h3 class="font-bold">${props.name}</h3>
                                    <p class="text-sm">${props.wilayah}</p>
                                </div>
                            `);
                            
                            areasLayer.addLayer(polygon);
                        }
                    });
                },
                
                async loadHotelsForAreaMode() {
                    const params = new URLSearchParams();
                    
                    // If kecamatan selected, filter by kecamatan
                    if (this.selectedKecamatan.length > 0) {
                        const response = await fetch('/api/hotels');
                        const geojson = await response.json();
                        
                        // Filter hotels by selected kecamatan
                        const filteredHotels = geojson.features.filter(feature => 
                            this.selectedKecamatan.includes(feature.properties.kecamatan)
                        );
                        
                        hotelsLayer.clearLayers();
                        this.hotelCount = filteredHotels.length;
                        
                        filteredHotels.forEach(feature => {
                            const coords = feature.geometry.coordinates;
                            const props = feature.properties;
                            
                            const marker = L.marker([coords[1], coords[0]]);
                            
                            // Open modal on click
                            marker.on('click', () => {
                                openHotelModal(props);
                            });
                            
                            hotelsLayer.addLayer(marker);
                        });
                    }
                    // If wilayah selected, load only those hotels
                    else if (this.selectedWilayah.length > 0) {
                        // Fetch hotels for each selected wilayah and combine
                        let allHotels = [];
                        for (const wilayah of this.selectedWilayah) {
                            const response = await fetch(`/api/hotels?wilayah=${wilayah}`);
                            const geojson = await response.json();
                            allHotels = allHotels.concat(geojson.features);
                        }
                        
                        hotelsLayer.clearLayers();
                        this.hotelCount = allHotels.length;
                        
                        allHotels.forEach(feature => {
                            const coords = feature.geometry.coordinates;
                            const props = feature.properties;
                            
                            const marker = L.marker([coords[1], coords[0]]);
                            
                            // Open modal on click
                            marker.on('click', () => {
                                openHotelModal(props);
                            });
                            
                            hotelsLayer.addLayer(marker);
                        });
                    } else {
                        // Load all hotels if no wilayah selected
                        await this.loadHotels();
                    }
                },
                
                applyFilters() {
                    this.changeMode();
                },
                
                resetFilters() {
                    this.filters = {
                        name: '',
                        wilayah: '',
                        stars: '',
                        price_min: '',
                        price_max: ''
                    };
                    this.applyFilters();
                }
            }
        }
        
        // Initialize on page load
        document.addEventListener('alpine:init', () => {
            // Hotels will auto-load via x-init directive
        });
    </script>
</body>
</html>
