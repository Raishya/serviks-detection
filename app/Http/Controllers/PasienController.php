<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PasienController extends Controller
{
    public function create()
    {
        Log::info('Menampilkan halaman tambah pasien');
        return view('pasien.addPasien');
    }

    public function store(Request $request)
    {
        // Validasi dan simpan data pasien
        $request->validate([
            'nama' => 'required|string|max:255',
            'usia' => 'required|integer',
            'tanggal_pemeriksaan' => 'required|date',
            'jenis_pemeriksaan' => 'required|string|max:255',
        ]);

        // Simpan data pasien ke dalam session untuk digunakan di halaman diagnosa
        $data = $request->only(['nama', 'usia', 'tanggal_pemeriksaan', 'jenis_pemeriksaan']);
        $request->session()->put('data', $data);

        // Log data pasien yang disimpan di session
        Log::info('Data pasien disimpan di session', $data);
        // Tambahkan log sebelum pengalihan rute
        Log::info('Mengalihkan ke halaman diagnosa');

        return redirect()->route('diagnosa');
    }

    public function diagnosa()
    {
        // Tampilkan halaman diagnosa dengan data pasien dari session
        $data = session()->get('data');
        Log::info('Menampilkan halaman diagnosa dengan data pasien', compact('data'));
        return view('pasien/diagnosa', compact('data'));
    }
}
