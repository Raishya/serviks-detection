@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tambah Data Pasien</h1>
        <form action="{{ route('diagnosas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="mb-3">
                <label for="usia" class="form-label">Usia</label>
                <input type="number" class="form-control" id="usia" name="usia" required>
            </div>
            <div class="mb-3">
                <label for="tanggal_pemeriksaan" class="form-label">Tanggal Pemeriksaan</label>
                <input type="date" class="form-control" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" required>
            </div>
            <div class="mb-3">
                <label for="jenis_pemeriksaan" class="form-label">Jenis Pemeriksaan</label>
                <input type="text" class="form-control" id="jenis_pemeriksaan" name="jenis_pemeriksaan" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Gambar</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <div class="mb-3">
                <label for="prediction" class="form-label">Prediksi</label>
                <input type="text" class="form-control" id="prediction" name="prediction" required readonly>
            </div>
            <div class="mb-3">
                <label for="confidence" class="form-label">Confidence</label>
                <input type="number" step="0.01" class="form-control" id="confidence" name="confidence" required
                    readonly>
            </div>
            <div class="mb-3">
                <label for="diagnosa" class="form-label">Kesimpulan</label>
                <textarea class="form-control" id="diagnosa" name="diagnosa" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const tanggalPemeriksaanInput = document.getElementById('tanggal_pemeriksaan');
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            const formattedDate = `${yyyy}-${mm}-${dd}`;
            tanggalPemeriksaanInput.value = formattedDate;
        });

        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        document.getElementById('image').addEventListener('change', previewImage);
    </script>
@endsection
