@extends('layouts.app')
@section('custom-css')
    <style>
        label {
            color: var(--secondary)
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
    <div class="container">
        <h1 class="fs-2 fw-bold">Edit Data Pasien</h1>
        <hr>
        <div class="row fs-5 fw-medium">
            <div class="col-md-6">
                <form id="edit-form" action="{{ route('diagnosas.update', $diagnosa->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama"
                            value="{{ $diagnosa->nama }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="usia" class="form-label">Usia</label>
                        <input type="number" class="form-control" id="usia" name="usia"
                            value="{{ $diagnosa->usia }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_pemeriksaan" class="form-label">Tanggal Pemeriksaan</label>
                        <input type="date" class="form-control" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan"
                            value="{{ $diagnosa->tanggal_pemeriksaan }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="jenis_pemeriksaan" class="form-label">Jenis Pemeriksaan</label>
                        <input type="text" class="form-control" id="jenis_pemeriksaan" name="jenis_pemeriksaan"
                            value="{{ $diagnosa->jenis_pemeriksaan }}" required>
                    </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="image" class="form-label">Gambar</label>
                    <input type="file" class="form-control" id="image" name="image" disabled>
                    <img src="{{ asset('storage/' . $diagnosa->image_path) }}" class="img-thumbnail mt-2"
                        style="max-width: 100px;">
                </div>
                <div class="mb-3">
                    <label for="prediction" class="form-label">Prediksi</label>
                    <input type="text" class="form-control" id="prediction" name="prediction"
                        value="{{ $diagnosa->prediction }}" disabled>
                </div>
                <div class="mb-3">
                    <label for="confidence" class="form-label">Confidence</label>
                    <input type="number" step="0.01" class="form-control" id="confidence" name="confidence"
                        value="{{ $diagnosa->confidence }}" disabled>
                </div>
                <div class="mb-3">
                    <label for="diagnosa" class="form-label">Kesimpulan</label>
                    <textarea class="form-control" id="diagnosa" name="diagnosa" rows="3" disabled>{{ $diagnosa->diagnosa }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Gambar</label>
                    <input type="file" class="form-control" id="canvas_output_path" name="canvas_output_path" disabled>
                    <img src="{{ asset('storage/' . $diagnosa->canvas_output_path) }}" class="img-thumbnail"
                        style="max-width: 100px;">
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Gambar</label>
                    <input type="file" class="form-control" id="mask_canvas_path" name="mask_canvas_path" disabled>
                    <img src="{{ asset('storage/' . $diagnosa->mask_canvas_path) }}" class="img-thumbnail"
                        style="max-width: 100px;">
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.edit-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Anda yakin ingin menyimpan data in?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        @if (session('success'))
            Swal.fire({
                title: 'Data Berhasil Disimpan!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
@endsection
