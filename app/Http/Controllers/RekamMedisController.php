<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diagnosa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RekamMedisController extends Controller
{
    public function rekamMedis(Request $request, $user_id)
    {
        try {
            $query = Diagnosa::where('user_id', $user_id);

            if ($request->filled('nama')) {
                $query->where('nama', 'LIKE', '%' . $request->nama . '%');
            }

            if ($request->filled('tanggal_pemeriksaan')) {
                $query->where('tanggal_pemeriksaan', $request->tanggal_pemeriksaan);
            }

            $diagnosas = $query->get();

            if ($diagnosas->isEmpty()) {
                Log::info('No records found', ['user_id' => $user_id, 'nama' => $request->nama, 'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan]);
                return back()->withErrors(['msg' => 'Data tidak ditemukan atau Anda tidak memiliki izin untuk mengakses data ini.']);
            }

            return view('rekam-medis', compact('diagnosas'));
        } catch (\Exception $e) {
            Log::error('Error fetching medical records', ['error' => $e->getMessage(), 'user_id' => $user_id]);
            return back()->withErrors(['msg' => 'Terjadi kesalahan saat mencari rekam medis.']);
        }
    }
}
