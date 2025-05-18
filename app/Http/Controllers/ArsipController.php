<?php

namespace App\Http\Controllers;

use App\Models\Diagnosa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $diagnosas = Diagnosa::all();
        return view('diagnosa.arsip', compact('diagnosas'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $diagnosas = Diagnosa::query()
            ->where('nama', 'LIKE', "%{$search}%")
            ->orWhere('tanggal_pemeriksaan', 'LIKE', "%{$search}%")
            ->get();

        return view('diagnosa.arsip', compact('diagnosas'));
    }

    public function create()
    {
        return view('diagnosa.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'usia' => 'required|integer',
            'tanggal_pemeriksaan' => 'required|date',
            'jenis_pemeriksaan' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'prediction' => 'required|string',
            'confidence' => 'required|numeric',
            'diagnosa' => 'required|string'
        ]);

        try {
            $imagePath = $request->file('image')->store('uploads', 'public');
            $validated['image_path'] = $imagePath;

            Diagnosa::create($validated);

            return redirect()->route('diagnosas.arsip')->with('success', 'Data pasien berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->route('diagnosas.create')->with('error', 'Data pasien gagal disimpan.');
        }
    }

    public function show(Diagnosa $diagnosa)
    {
        if (auth()->user()->type === 'user' && auth()->user()->name !== $diagnosa->nama) {
            abort(403, 'Unauthorized action.');
        }
        return view('diagnosa.show', compact('diagnosa'));
    }

    public function edit(Diagnosa $diagnosa)
    {
        return view('diagnosa.edit', compact('diagnosa'));
    }

    public function update(Request $request, Diagnosa $diagnosa)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'usia' => 'required|integer',
            'tanggal_pemeriksaan' => 'required|date',
            'jenis_pemeriksaan' => 'required|string|max:255',
        ]);
        try {
            $diagnosa->update($validated);
            session()->flash('success', 'Data pasien berhasil diupdate.');
            return redirect()->route('diagnosa.arsip'); // Arahkan ke view arsip setelah berhasil update
        } catch (\Exception $e) {
            return redirect()->route('diagnosas.edit', $diagnosa->id)->with('error', 'Data pasien gagal diperbarui.');
        }
    }



    public function destroy(Diagnosa $diagnosa)
    {
        Storage::disk('public')->delete($diagnosa->image_path);
        $diagnosa->delete();
        session()->flash('success', 'Data pasien berhasil dihapus.');

        return redirect()->route('diagnosas.index')->with('success', 'Data pasien berhasil dihapus.');
    }
}
