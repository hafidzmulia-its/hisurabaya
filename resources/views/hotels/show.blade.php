<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $hotel->NamaObjek }}
            </h2>
            <div class="flex gap-2">
                @can('update', $hotel)
                    <a href="{{ route('hotels.edit', $hotel->PointID) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Edit Hotel
                    </a>
                @endcan
                <a href="{{ route('hotels.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Images Gallery -->
                    @if($hotel->images->count() > 0)
                        <div class="mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($hotel->images as $image)
                                    @php
                                        $imageUrl = $image->ImageURL;
                                        $imageSrc = str_starts_with($imageUrl, 'http') ? $imageUrl : asset('storage/' . $imageUrl);
                                    @endphp
                                    <img src="{{ $imageSrc }}" alt="{{ $hotel->NamaObjek }}" class="w-full h-64 object-cover rounded-lg">
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Hotel Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
                            <dl class="space-y-2">
                                <div class="flex">
                                    <dt class="font-medium text-gray-700 w-1/3">Name:</dt>
                                    <dd class="text-gray-900">{{ $hotel->NamaObjek }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-700 w-1/3">Star Rating:</dt>
                                    <dd class="text-gray-900">
                                        @for($i = 0; $i < $hotel->StarClass; $i++)
                                            <span class="text-yellow-400">⭐</span>
                                        @endfor
                                        ({{ $hotel->StarClass }} Star)
                                    </dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-700 w-1/3">Status:</dt>
                                    <dd>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $hotel->IsActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $hotel->IsActive ? 'Active' : 'Inactive' }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-700 w-1/3">Owner:</dt>
                                    <dd class="text-gray-900">{{ $hotel->owner->name ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Location</h3>
                            <dl class="space-y-2">
                                <div class="flex">
                                    <dt class="font-medium text-gray-700 w-1/3">Address:</dt>
                                    <dd class="text-gray-900">{{ $hotel->Alamat }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-700 w-1/3">Kecamatan:</dt>
                                    <dd class="text-gray-900">{{ $hotel->kecamatan->NamaKecamatan ?? '-' }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-700 w-1/3">Wilayah:</dt>
                                    <dd class="text-gray-900">{{ $hotel->kecamatan->Wilayah ?? '-' }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium text-gray-700 w-1/3">Coordinates:</dt>
                                    <dd class="text-gray-900">{{ $hotel->Latitude }}, {{ $hotel->Longitude }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($hotel->Deskripsi)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">Description</h3>
                            <p class="text-gray-700">{{ $hotel->Deskripsi }}</p>
                        </div>
                    @endif

                    <!-- Price Range -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Price Range</h3>
                        <p class="text-2xl font-bold text-blue-600">
                            Rp {{ number_format($hotel->HargaMin, 0, ',', '.') }} - Rp {{ number_format($hotel->HargaMax, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Facilities -->
                    <!-- @if($hotel->facilities->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Facilities</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($hotel->facilities as $facility)
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <span class="text-green-500 mr-2">✓</span>
                                        <div>
                                            <div class="font-medium">{{ $facility->Name }}</div>
                                            @if($facility->pivot->ExtraPrice > 0)
                                                <div class="text-sm text-gray-500">+Rp {{ number_format($facility->pivot->ExtraPrice, 0, ',', '.') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif -->

                    <!-- Map Preview -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Location Map</h3>
                        <div id="map" style="height: 400px; width: 100%;" class="rounded-lg border"></div>
                    </div>
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
            const map = L.map('map').setView([{{ $hotel->Latitude }}, {{ $hotel->Longitude }}], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            L.marker([{{ $hotel->Latitude }}, {{ $hotel->Longitude }}])
                .addTo(map)
                .bindPopup('<b>{{ addslashes($hotel->NamaObjek) }}</b><br>{{ addslashes($hotel->Alamat) }}')
                .openPopup();
            
            // Force map to recalculate size
            setTimeout(() => map.invalidateSize(), 100);
        });
    </script>
    @endpush
</x-app-layout>
