<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::withCount('hotels')->orderBy('Wilayah')->orderBy('NamaKecamatan')->paginate(15);
        return view('kecamatan.index', compact('kecamatans'));
    }

    public function create()
    {
        return view('kecamatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'NamaKecamatan' => 'required|max:255|unique:kecamatan,NamaKecamatan',
            'Wilayah' => 'required|in:Surabaya Barat,Surabaya Timur,Surabaya Utara,Surabaya Selatan,Surabaya Tengah',
            'PolygonJSON' => 'nullable|json',
        ]);

        Kecamatan::create($validated);

        return redirect()->route('kecamatan.index')->with('success', 'Kecamatan created successfully!');
    }

    public function show(Kecamatan $kecamatan)
    {
        $kecamatan->loadCount('hotels');
        return view('kecamatan.show', compact('kecamatan'));
    }

    public function edit(Kecamatan $kecamatan)
    {
        return view('kecamatan.edit', compact('kecamatan'));
    }

    public function update(Request $request, Kecamatan $kecamatan)
    {
        $validated = $request->validate([
            'NamaKecamatan' => 'required|max:255|unique:kecamatan,NamaKecamatan,' . $kecamatan->KecamatanID . ',KecamatanID',
            'Wilayah' => 'required|in:Surabaya Barat,Surabaya Timur,Surabaya Utara,Surabaya Selatan,Surabaya Tengah',
            'PolygonJSON' => 'nullable|json',
        ]);

        $kecamatan->update($validated);

        return redirect()->route('kecamatan.index')->with('success', 'Kecamatan updated successfully!');
    }

    public function destroy(Kecamatan $kecamatan)
    {
        if ($kecamatan->hotels()->count() > 0) {
            return redirect()->route('kecamatan.index')->with('error', 'Cannot delete kecamatan with existing hotels.');
        }

        $kecamatan->delete();
        return redirect()->route('kecamatan.index')->with('success', 'Kecamatan deleted successfully!');
    }
}
