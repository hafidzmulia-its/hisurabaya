<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Hotel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('hotels.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('hotels._form')
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map
            const lat = parseFloat(document.getElementById('Latitude').value) || -7.2575;
            const lng = parseFloat(document.getElementById('Longitude').value) || 112.7521;
            
            const map = L.map('locationMap').setView([lat, lng], 13);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            // Add marker
            let marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            
            // Reverse geocoding function
            async function reverseGeocode(lat, lng) {
                try {
                    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`);
                    const data = await response.json();
                    
                    if (data && data.address) {
                        const address = data.address;
                        
                        // Build address string
                        let addressParts = [];
                        if (address.road) addressParts.push(address.road);
                        if (address.house_number) addressParts.push('No.' + address.house_number);
                        if (address.suburb) addressParts.push(address.suburb);
                        if (address.city) addressParts.push(address.city);
                        
                        const fullAddress = addressParts.join(', ');
                        if (fullAddress) {
                            document.getElementById('Alamat').value = fullAddress;
                        }
                        
                        // Try to match kecamatan
                        const suburb = address.suburb || address.neighbourhood || address.quarter || '';
                        if (suburb) {
                            const kecamatanSelect = document.getElementById('KecamatanID');
                            const options = Array.from(kecamatanSelect.options);
                            
                            // Try exact match first
                            let matchedOption = options.find(opt => 
                                opt.text.toLowerCase().includes(suburb.toLowerCase())
                            );
                            
                            if (matchedOption) {
                                kecamatanSelect.value = matchedOption.value;
                                console.log('Auto-selected kecamatan:', matchedOption.text);
                            } else {
                                console.log('No matching kecamatan found for:', suburb);
                            }
                        }
                    }
                } catch (error) {
                    console.error('Reverse geocoding error:', error);
                }
            }
            
            // Update coordinates and address when marker is dragged
            marker.on('dragend', function(e) {
                const position = marker.getLatLng();
                document.getElementById('Latitude').value = position.lat.toFixed(8);
                document.getElementById('Longitude').value = position.lng.toFixed(8);
                reverseGeocode(position.lat, position.lng);
            });
            
            // Update marker and address when clicking on map
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                document.getElementById('Latitude').value = e.latlng.lat.toFixed(8);
                document.getElementById('Longitude').value = e.latlng.lng.toFixed(8);
                reverseGeocode(e.latlng.lat, e.latlng.lng);
            });
            
            // Force map to recalculate size
            setTimeout(() => map.invalidateSize(), 100);
            
            // Image preview functionality
            const imageInput = document.getElementById('images');
            const previewContainer = document.getElementById('imagePreviewContainer');
            
            imageInput.addEventListener('change', function(e) {
                previewContainer.innerHTML = '';
                const files = Array.from(e.target.files);
                
                if (files.length > 0) {
                    previewContainer.classList.remove('hidden');
                    
                    files.forEach((file, index) => {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            
                            reader.onload = function(e) {
                                const div = document.createElement('div');
                                div.className = 'relative';
                                div.innerHTML = `
                                    <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-32 object-cover rounded">
                                    <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                        ${(file.size / 1024).toFixed(0)} KB
                                    </div>
                                `;
                                previewContainer.appendChild(div);
                            };
                            
                            reader.readAsDataURL(file);
                        }
                    });
                } else {
                    previewContainer.classList.add('hidden');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
