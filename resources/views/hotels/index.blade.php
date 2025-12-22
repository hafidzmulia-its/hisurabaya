<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Hotel Management') }}
            </h2>
            @can('create', App\Models\ObjekPoint::class)
                <a href="{{ route('hotels.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    + Add New Hotel
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('hotels.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Hotel name..." class="w-full border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Wilayah</label>
                            <select name="wilayah" class="w-full border-gray-300 rounded-lg">
                                <option value="">All Wilayah</option>
                                <option value="Surabaya Barat" {{ request('wilayah') == 'Surabaya Barat' ? 'selected' : '' }}>Surabaya Barat</option>
                                <option value="Surabaya Timur" {{ request('wilayah') == 'Surabaya Timur' ? 'selected' : '' }}>Surabaya Timur</option>
                                <option value="Surabaya Utara" {{ request('wilayah') == 'Surabaya Utara' ? 'selected' : '' }}>Surabaya Utara</option>
                                <option value="Surabaya Selatan" {{ request('wilayah') == 'Surabaya Selatan' ? 'selected' : '' }}>Surabaya Selatan</option>
                                <option value="Surabaya Tengah" {{ request('wilayah') == 'Surabaya Tengah' ? 'selected' : '' }}>Surabaya Tengah</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Star Rating</label>
                            <select name="stars" class="w-full border-gray-300 rounded-lg">
                                <option value="">All Stars</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ request('stars') == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex-1">Filter</button>
                            <a href="{{ route('hotels.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Hotels Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hotel</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price Range</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    @if(auth()->user()->role === 'admin')
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                                    @endif
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($hotels as $hotel)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($hotel->images->first())
                                                        @php
                                                            $imageUrl = $hotel->images->first()->ImageURL;
                                                            $imageSrc = str_starts_with($imageUrl, 'http') ? $imageUrl : asset('storage/' . $imageUrl);
                                                        @endphp
                                                        <img class="h-10 w-10 rounded object-cover" src="{{ $imageSrc }}" alt="{{ $hotel->NamaObjek }}">
                                                    @else
                                                        <div class="h-10 w-10 rounded bg-gray-200 flex items-center justify-center">
                                                            <span class="text-gray-500 text-xs">No img</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $hotel->NamaObjek }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $hotel->kecamatan->NamaKecamatan ?? '-' }}</div>
                                            <div class="text-sm text-gray-500">{{ $hotel->kecamatan->Wilayah ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Rp {{ number_format($hotel->HargaMin, 0, ',', '.') }}</div>
                                            <div class="text-sm text-gray-500">- Rp {{ number_format($hotel->HargaMax, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-yellow-400">⭐</span>
                                                <span class="ml-1 text-sm text-gray-900">{{ $hotel->StarClass }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $hotel->IsActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $hotel->IsActive ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        @if(auth()->user()->role === 'admin')
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $hotel->owner->name ?? '-' }}
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('hotels.show', $hotel->PointID) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                            @can('update', $hotel)
                                                <a href="{{ route('hotels.edit', $hotel->PointID) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                            @endcan
                                            @can('delete', $hotel)
                                                <form action="{{ route('hotels.destroy', $hotel->PointID) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this hotel?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->role === 'admin' ? '7' : '6' }}" class="px-6 py-4 text-center text-gray-500">
                                            No hotels found. 
                                            @can('create', App\Models\ObjekPoint::class)
                                                <a href="{{ route('hotels.create') }}" class="text-blue-600 hover:underline">Add your first hotel</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $hotels->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
