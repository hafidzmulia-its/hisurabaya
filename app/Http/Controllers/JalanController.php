<?php

namespace App\Http\Controllers;

use App\Models\Jalan;
use App\Models\ObjekPoint;
use Illuminate\Http\Request;

class JalanController extends Controller
{
    public function index()
    {
        $jalans = Jalan::with(['startPoint', 'endPoint'])->orderBy('created_at', 'desc')->paginate(15);
        return view('jalan.index', compact('jalans'));
    }

    public function create()
    {
        $hotels = ObjekPoint::orderBy('NamaObjek')->get();
        return view('jalan.create', compact('hotels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'NamaJalan' => 'required|max:255',
            'StartPointID' => 'required|exists:objekpoint,PointID',
            'EndPointID' => 'required|exists:objekpoint,PointID|different:StartPointID',
            'DistanceKM' => 'required|numeric|min:0',
            'KoordinatJSON' => 'required|json',
        ]);

        Jalan::create($validated);

        return redirect()->route('jalan.index')->with('success', 'Route created successfully!');
    }

    public function show(Jalan $jalan)
    {
        $jalan->load(['startPoint', 'endPoint']);
        return view('jalan.show', compact('jalan'));
    }

    public function edit(Jalan $jalan)
    {
        $hotels = ObjekPoint::orderBy('NamaObjek')->get();
        return view('jalan.edit', compact('jalan', 'hotels'));
    }

    public function update(Request $request, Jalan $jalan)
    {
        $validated = $request->validate([
            'NamaJalan' => 'required|max:255',
            'StartPointID' => 'required|exists:objekpoint,PointID',
            'EndPointID' => 'required|exists:objekpoint,PointID|different:StartPointID',
            'DistanceKM' => 'required|numeric|min:0',
            'KoordinatJSON' => 'required|json',
        ]);

        $jalan->update($validated);

        return redirect()->route('jalan.index')->with('success', 'Route updated successfully!');
    }

    public function destroy(Jalan $jalan)
    {
        $jalan->delete();
        return redirect()->route('jalan.index')->with('success', 'Route deleted successfully!');
    }
}
