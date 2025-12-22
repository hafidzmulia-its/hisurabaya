<?php

namespace App\Http\Controllers;

use App\Models\ObjekPoint;
use App\Models\Kecamatan;
use App\Models\FacilityType;
use App\Models\ObjekPointImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class HotelController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of hotels.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', ObjekPoint::class);
        
        $query = ObjekPoint::with(['kecamatan', 'owner', 'images']);
        
        // Admin sees all hotels, Owner sees only their hotels
        if (auth()->user()->role === 'owner') {
            $query->where('OwnerUserID', auth()->id());
        }
        
        // Apply filters
        if ($request->filled('search')) {
            $query->where('NamaObjek', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('wilayah')) {
            $query->wilayah($request->wilayah);
        }
        
        if ($request->filled('stars')) {
            $query->starClass($request->stars);
        }
        
        $hotels = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('hotels.index', compact('hotels'));
    }

    /**
     * Show the form for creating a new hotel.
     */
    public function create()
    {
        $this->authorize('create', ObjekPoint::class);
        
        $kecamatans = Kecamatan::orderBy('NamaKecamatan')->get();
        $facilities = FacilityType::orderBy('Name')->get();
        
        return view('hotels.create', compact('kecamatans', 'facilities'));
    }

    /**
     * Store a newly created hotel in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', ObjekPoint::class);
        
        $validated = $request->validate([
            'NamaObjek' => 'required|max:255',
            'Deskripsi' => 'nullable',
            'Alamat' => 'required',
            'Latitude' => 'required|numeric|between:-90,90',
            'Longitude' => 'required|numeric|between:-180,180',
            'HargaMin' => 'required|numeric|min:0',
            'HargaMax' => 'required|numeric|min:0|gte:HargaMin',
            'StarClass' => 'required|integer|between:1,5',
            'KecamatanID' => 'required|exists:kecamatan,KecamatanID',
            'IsActive' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'facilities' => 'nullable|array',
            'facilities.*.id' => 'exists:facility_types,FacilityID',
            'facilities.*.available' => 'boolean',
            'facilities.*.extra_price' => 'nullable|numeric|min:0',
        ]);
        
        DB::beginTransaction();
        try {
            // Create hotel
            $hotel = ObjekPoint::create([
                'NamaObjek' => $validated['NamaObjek'],
                'Deskripsi' => $validated['Deskripsi'],
                'Alamat' => $validated['Alamat'],
                'Latitude' => $validated['Latitude'],
                'Longitude' => $validated['Longitude'],
                'HargaMin' => $validated['HargaMin'],
                'HargaMax' => $validated['HargaMax'],
                'StarClass' => $validated['StarClass'],
                'KecamatanID' => $validated['KecamatanID'],
                'OwnerUserID' => auth()->id(),
                'IsActive' => $request->boolean('IsActive', true),
            ]);
            
            // Handle images with compression
            if ($request->hasFile('images')) {
                $manager = new ImageManager(new Driver());
                
                foreach ($request->file('images') as $image) {
                    // Generate unique filename
                    $filename = uniqid('hotel_') . '.jpg';
                    $path = 'hotels/' . $filename;
                    
                    // Resize and compress image
                    $img = $manager->read($image);
                    
                    // Resize if larger than 1200px width, maintain aspect ratio
                    if ($img->width() > 1200) {
                        $img->scale(width: 1200);
                    }
                    
                    // Convert to JPEG and compress to 80% quality
                    $encoded = $img->toJpeg(quality: 80);
                    
                    // Save to storage
                    Storage::disk('public')->put($path, (string) $encoded);
                    
                    ObjekPointImage::create([
                        'PointID' => $hotel->PointID,
                        'ImageURL' => $path,
                    ]);
                }
            }
            
            // Handle facilities
            if ($request->has('facilities')) {
                $facilitiesData = [];
                foreach ($request->facilities as $facility) {
                    if (isset($facility['available']) && $facility['available']) {
                        $facilitiesData[$facility['id']] = [
                            'IsAvailable' => true,
                            'ExtraPrice' => $facility['extra_price'] ?? 0,
                        ];
                    }
                }
                $hotel->facilities()->sync($facilitiesData);
            }
            
            DB::commit();
            return redirect()->route('hotels.index')->with('success', 'Hotel created successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create hotel: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified hotel.
     */
    public function show(ObjekPoint $hotel)
    {
        $this->authorize('view', $hotel);
        
        $hotel->load(['kecamatan', 'owner', 'images', 'facilities']);
        
        return view('hotels.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified hotel.
     */
    public function edit(ObjekPoint $hotel)
    {
        $this->authorize('update', $hotel);
        
        $kecamatans = Kecamatan::orderBy('NamaKecamatan')->get();
        $facilities = FacilityType::orderBy('Name')->get();
        $hotel->load(['images', 'facilities']);
        
        return view('hotels.edit', compact('hotel', 'kecamatans', 'facilities'));
    }

    /**
     * Update the specified hotel in storage.
     */
    public function update(Request $request, ObjekPoint $hotel)
    {
        $this->authorize('update', $hotel);
        
        $validated = $request->validate([
            'NamaObjek' => 'required|max:255',
            'Deskripsi' => 'nullable',
            'Alamat' => 'required',
            'Latitude' => 'required|numeric|between:-90,90',
            'Longitude' => 'required|numeric|between:-180,180',
            'HargaMin' => 'required|numeric|min:0',
            'HargaMax' => 'required|numeric|min:0|gte:HargaMin',
            'StarClass' => 'required|integer|between:1,5',
            'KecamatanID' => 'required|exists:kecamatan,KecamatanID',
            'IsActive' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:objekpoint_images,ImageID',
            'facilities' => 'nullable|array',
            'facilities.*.id' => 'exists:facility_types,FacilityID',
            'facilities.*.available' => 'boolean',
            'facilities.*.extra_price' => 'nullable|numeric|min:0',
        ]);
        
        DB::beginTransaction();
        try {
            // Update hotel
            $hotel->update([
                'NamaObjek' => $validated['NamaObjek'],
                'Deskripsi' => $validated['Deskripsi'],
                'Alamat' => $validated['Alamat'],
                'Latitude' => $validated['Latitude'],
                'Longitude' => $validated['Longitude'],
                'HargaMin' => $validated['HargaMin'],
                'HargaMax' => $validated['HargaMax'],
                'StarClass' => $validated['StarClass'],
                'KecamatanID' => $validated['KecamatanID'],
                'IsActive' => $request->boolean('IsActive', true),
            ]);
            
            // Delete selected images
            if ($request->has('delete_images')) {
                $imagesToDelete = ObjekPointImage::whereIn('ImageID', $request->delete_images)
                    ->where('PointID', $hotel->PointID)
                    ->get();
                    
                foreach ($imagesToDelete as $image) {
                    Storage::disk('public')->delete($image->ImageURL);
                    $image->delete();
                }
            }
            
            // Add new images with compression
            if ($request->hasFile('images')) {
                $manager = new ImageManager(new Driver());
                
                foreach ($request->file('images') as $image) {
                    // Generate unique filename
                    $filename = uniqid('hotel_') . '.jpg';
                    $path = 'hotels/' . $filename;
                    
                    // Resize and compress image
                    $img = $manager->read($image);
                    
                    // Resize if larger than 1200px width, maintain aspect ratio
                    if ($img->width() > 1200) {
                        $img->scale(width: 1200);
                    }
                    
                    // Convert to JPEG and compress to 80% quality
                    $encoded = $img->toJpeg(quality: 80);
                    
                    // Save to storage
                    Storage::disk('public')->put($path, (string) $encoded);
                    
                    ObjekPointImage::create([
                        'PointID' => $hotel->PointID,
                        'ImageURL' => $path,
                    ]);
                }
            }
            
            // Update facilities
            if ($request->has('facilities')) {
                $facilitiesData = [];
                foreach ($request->facilities as $facility) {
                    if (isset($facility['available']) && $facility['available']) {
                        $facilitiesData[$facility['id']] = [
                            'IsAvailable' => true,
                            'ExtraPrice' => $facility['extra_price'] ?? 0,
                        ];
                    }
                }
                $hotel->facilities()->sync($facilitiesData);
            }
            
            DB::commit();
            return redirect()->route('hotels.index')->with('success', 'Hotel updated successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update hotel: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified hotel from storage.
     */
    public function destroy(ObjekPoint $hotel)
    {
        $this->authorize('delete', $hotel);
        
        DB::beginTransaction();
        try {
            // Delete images from storage
            foreach ($hotel->images as $image) {
                Storage::disk('public')->delete($image->ImageURL);
                $image->delete();
            }
            
            // Delete hotel (facilities will be auto-deleted via cascade or detach)
            $hotel->facilities()->detach();
            $hotel->delete();
            
            DB::commit();
            return redirect()->route('hotels.index')->with('success', 'Hotel deleted successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete hotel: ' . $e->getMessage());
        }
    }
}
