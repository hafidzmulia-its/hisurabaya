<div class="space-y-6">
    <!-- Basic Information -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label for="NamaObjek" class="block text-sm font-medium text-gray-700 mb-1">Hotel Name *</label>
                <input type="text" name="NamaObjek" id="NamaObjek" value="{{ old('NamaObjek', $hotel->NamaObjek ?? '') }}" required class="w-full border-gray-300 rounded-lg @error('NamaObjek') border-red-500 @enderror">
                @error('NamaObjek')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="Deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="Deskripsi" id="Deskripsi" rows="4" class="w-full border-gray-300 rounded-lg @error('Deskripsi') border-red-500 @enderror">{{ old('Deskripsi', $hotel->Deskripsi ?? '') }}</textarea>
                @error('Deskripsi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="Alamat" class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                <input type="text" name="Alamat" id="Alamat" value="{{ old('Alamat', $hotel->Alamat ?? '') }}" required class="w-full border-gray-300 rounded-lg @error('Alamat') border-red-500 @enderror">
                @error('Alamat')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="KecamatanID" class="block text-sm font-medium text-gray-700 mb-1">Kecamatan *</label>
                <select name="KecamatanID" id="KecamatanID" required class="w-full border-gray-300 rounded-lg @error('KecamatanID') border-red-500 @enderror">
                    <option value="">Select Kecamatan</option>
                    @foreach($kecamatans as $kecamatan)
                        <option value="{{ $kecamatan->KecamatanID }}" {{ old('KecamatanID', $hotel->KecamatanID ?? '') == $kecamatan->KecamatanID ? 'selected' : '' }}>
                            {{ $kecamatan->NamaKecamatan }} ({{ $kecamatan->Wilayah }})
                        </option>
                    @endforeach
                </select>
                @error('KecamatanID')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="StarClass" class="block text-sm font-medium text-gray-700 mb-1">Star Rating *</label>
                <select name="StarClass" id="StarClass" required class="w-full border-gray-300 rounded-lg @error('StarClass') border-red-500 @enderror">
                    <option value="">Select Rating</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ old('StarClass', $hotel->StarClass ?? '') == $i ? 'selected' : '' }}>{{ $i }} Star</option>
                    @endfor
                </select>
                @error('StarClass')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Location with Interactive Map -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-4">Location (Click on map to set coordinates)</h3>
        
        <!-- Map Container -->
        <div id="locationMap" style="height: 400px; width: 100%;" class="rounded-lg border mb-4"></div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="Latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude *</label>
                <input type="number" step="0.00000001" name="Latitude" id="Latitude" value="{{ old('Latitude', $hotel->Latitude ?? '-7.2575') }}" required class="w-full border-gray-300 rounded-lg @error('Latitude') border-red-500 @enderror">
                @error('Latitude')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="Longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude *</label>
                <input type="number" step="0.00000001" name="Longitude" id="Longitude" value="{{ old('Longitude', $hotel->Longitude ?? '112.7521') }}" required class="w-full border-gray-300 rounded-lg @error('Longitude') border-red-500 @enderror">
                @error('Longitude')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <p class="text-sm text-blue-600 mt-2">📍 Click anywhere on the map to set the hotel location (auto-fills address & kecamatan)</p>
    </div>

    <!-- Price Range -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-4">Price Range (IDR)</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="HargaMin" class="block text-sm font-medium text-gray-700 mb-1">Minimum Price *</label>
                <input type="number" step="1000" name="HargaMin" id="HargaMin" value="{{ old('HargaMin', $hotel->HargaMin ?? '') }}" required class="w-full border-gray-300 rounded-lg @error('HargaMin') border-red-500 @enderror">
                @error('HargaMin')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="HargaMax" class="block text-sm font-medium text-gray-700 mb-1">Maximum Price *</label>
                <input type="number" step="1000" name="HargaMax" id="HargaMax" value="{{ old('HargaMax', $hotel->HargaMax ?? '') }}" required class="w-full border-gray-300 rounded-lg @error('HargaMax') border-red-500 @enderror">
                @error('HargaMax')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Images -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-4">Hotel Images</h3>
        
        @if(isset($hotel) && $hotel->images->count() > 0)
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($hotel->images as $image)
                        <div class="relative">
                            @php
                                $imageUrl = $image->ImageURL;
                                $imageSrc = str_starts_with($imageUrl, 'http') ? $imageUrl : asset('storage/' . $imageUrl);
                            @endphp
                            <img src="{{ $imageSrc }}" alt="Hotel image" class="w-full h-32 object-cover rounded">
                            <label class="absolute top-2 right-2 bg-white rounded px-2 py-1 text-xs cursor-pointer hover:bg-red-50">
                                <input type="checkbox" name="delete_images[]" value="{{ $image->ImageID }}" class="mr-1">
                                Delete
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div>
            <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Add New Images</label>
            <input type="file" name="images[]" id="images" multiple accept="image/*" class="w-full border-gray-300 rounded-lg @error('images.*') border-red-500 @enderror">
            <p class="text-xs text-gray-500 mt-1">You can select multiple images (JPEG, PNG, GIF, max 2MB each)</p>
            @error('images.*')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Image Preview Container -->
        <div id="imagePreviewContainer" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 hidden"></div>
    </div>

    <!-- Facilities -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-4">Facilities</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($facilities as $facility)
                @php
                    $hotelFacility = isset($hotel) ? $hotel->facilities->find($facility->FacilityID) : null;
                    $isChecked = old('facilities.' . $loop->index . '.available', $hotelFacility ? true : false);
                    $extraPrice = old('facilities.' . $loop->index . '.extra_price', $hotelFacility?->pivot->ExtraPrice ?? 0);
                @endphp
                <div class="flex items-center p-3 border rounded-lg">
                    <input type="hidden" name="facilities[{{ $loop->index }}][id]" value="{{ $facility->FacilityID }}">
                    <input type="checkbox" name="facilities[{{ $loop->index }}][available]" id="facility_{{ $facility->FacilityID }}" value="1" {{ $isChecked ? 'checked' : '' }} class="rounded mr-3">
                    <div class="flex-1">
                        <label for="facility_{{ $facility->FacilityID }}" class="font-medium cursor-pointer">{{ $facility->Name }}</label>
                        <input type="number" name="facilities[{{ $loop->index }}][extra_price]" placeholder="Extra price (optional)" value="{{ $extraPrice }}" class="w-full text-sm border-gray-300 rounded mt-1">
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Status -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-4">Status</h3>
        <div class="flex items-center">
            <input type="checkbox" name="IsActive" id="IsActive" value="1" {{ old('IsActive', $hotel->IsActive ?? true) ? 'checked' : '' }} class="rounded mr-2">
            <label for="IsActive" class="text-sm font-medium text-gray-700">Active (visible on map)</label>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="flex gap-4">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
            {{ isset($hotel) ? 'Update Hotel' : 'Create Hotel' }}
        </button>
        <a href="{{ route('hotels.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg">
            Cancel
        </a>
    </div>
</div>
