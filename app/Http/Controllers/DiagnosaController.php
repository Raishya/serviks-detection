<?php

// app/Http/Controllers/DiagnosaController.php

namespace App\Http\Controllers;

use App\Models\Diagnosa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DiagnosaController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->session()->get('data');
        $result = $request->session()->get('result');
        $imagePath = $request->session()->get('imagePath');
        $canvasOutputPath = $request->session()->get('canvasOutputPath');
        $maskCanvasPath = $request->session()->get('maskCanvasPath');

        if (app()->environment('local')) {
            Log::info('Menampilkan halaman diagnosa', compact('data', 'result', 'imagePath', 'canvasOutputPath', 'maskCanvasPath'));
        }
        Log::info('Session data:', session()->all());
        return view('pasien.diagnosa', compact('data', 'result', 'imagePath', 'canvasOutputPath', 'maskCanvasPath'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'diagnosa' => 'required',
            'prediction' => 'required',
            'canvas_output_path' => 'required|string|max:255',
            'mask_canvas_path' => 'required|string|max:255',
        ]);

        $data = $request->session()->get('data');
        $imagePath = $request->session()->get('imagePath');
        $canvasOutputPath = $request->input('canvas_output_path');
        $maskCanvasPath = $request->input('mask_canvas_path');

        Log::info('Data session saat save', compact('data', 'imagePath', 'canvasOutputPath', 'maskCanvasPath'));

        if (is_null($data) || is_null($imagePath)) {
            Log::error('Data sesi hilang', compact('data', 'imagePath', 'canvasOutputPath', 'maskCanvasPath'));
            $this->cleanupStorage([$imagePath, $canvasOutputPath, $maskCanvasPath]);
            return redirect()->route('diagnosa')->withErrors(['msg' => 'Data sesi hilang. Silakan coba lagi.']);
        }

        $result = json_decode($request->prediction, true);

        try {
            Log::info('Mencoba menyimpan diagnosa', compact('data', 'result'));

            $diagnosa = new Diagnosa();
            $diagnosa->nama = $data['nama'];
            $diagnosa->usia = $data['usia'];
            $diagnosa->user_id = Auth::id();
            $diagnosa->tanggal_pemeriksaan = $data['tanggal_pemeriksaan'];
            $diagnosa->jenis_pemeriksaan = $data['jenis_pemeriksaan'];
            $diagnosa->image_path = $imagePath;
            $diagnosa->canvas_output_path = $canvasOutputPath;
            $diagnosa->mask_canvas_path = $maskCanvasPath;
            $diagnosa->prediction = $result['prediction'];
            $diagnosa->confidence = $result['confidence'];
            $diagnosa->diagnosa = $request->diagnosa;
            $diagnosa->save();

            // Hapus sesi setelah berhasil disimpan ke database
            $request->session()->forget(['data', 'result', 'imagePath', 'canvasOutputPath', 'maskCanvasPath']);

            return redirect()->route('diagnosa.arsip')->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan diagnosa', compact('e'));
            $this->cleanupStorage([$imagePath, $canvasOutputPath, $maskCanvasPath]);
            return redirect()->route('diagnosa')->withErrors(['msg' => 'Terjadi kesalahan saat menyimpan data.']);
        }
    }

    public function saveImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->file('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $request->session()->put('imagePath', $imagePath);
            return response()->json(['success' => 'Image uploaded successfully', 'imagePath' => $imagePath]);
        }

        return response()->json(['error' => 'Image upload failed'], 400);
    }

    public function saveImageFromCamera(Request $request)
    {
        $request->validate([
            'image' => 'required|string'
        ]);

        try {
            $imageData = $request->image;
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = 'camera_' . time() . '.png';
            Storage::disk('public')->put('images/' . $imageName, base64_decode($imageData));

            $request->session()->put('imagePath', 'images/' . $imageName);

            return response()->json(['success' => true, 'imagePath' => 'images/' . $imageName]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan gambar dari kamera', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => 'Gagal menyimpan gambar.']);
        }
    }

    public function saveCanvasImages(Request $request)
    {
        $request->validate([
            'canvasOutput' => 'required|string',
            'maskCanvas' => 'required|string'
        ]);

        try {
            $canvasOutputData = $request->canvasOutput;
            $maskCanvasData = $request->maskCanvas;

            $canvasOutputData = str_replace('data:image/png;base64,', '', $canvasOutputData);
            $canvasOutputData = base64_decode($canvasOutputData);
            $canvasOutputName = 'canvas_output_' . time() . '.png';
            Storage::disk('public')->put('canvas/' . $canvasOutputName, $canvasOutputData);

            $maskCanvasData = str_replace('data:image/png;base64,', '', $maskCanvasData);
            $maskCanvasData = base64_decode($maskCanvasData);
            $maskCanvasName = 'mask_canvas_' . time() . '.png';
            Storage::disk('public')->put('canvas/' . $maskCanvasName, $maskCanvasData);

            // Update session with new paths
            $request->session()->put('canvasOutputPath', 'canvas/' . $canvasOutputName);
            $request->session()->put('maskCanvasPath', 'canvas/' . $maskCanvasName);

            return response()->json([
                'success' => true,
                'canvasOutputPath' => 'canvas/' . $canvasOutputName,
                'maskCanvasPath' => 'canvas/' . $maskCanvasName
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving canvas images:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => 'Error saving canvas images.']);
        }
    }

    public function arsip()
    {
        $diagnosas = Diagnosa::all();
        return view('diagnosa.arsip', compact('diagnosas'));
    }

    public function kolposkop()
    {
        return view('kolposkop.kolposkop');
    }

    private function cleanupStorage(array $paths)
    {
        foreach ($paths as $path) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
