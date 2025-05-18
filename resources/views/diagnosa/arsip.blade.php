@extends('layouts.app')

@section('custom-css')
    @vite(['resources/css/pages/arsip.css'])
@endsection

@section('content')
    <div class="container">
        <h1 class="fs-1 mb-3">Arsip Data Pasien</h1>
        <form method="GET" action="{{ route('diagnosa.search') }}">
            <div class="input-group mb-4">
                <input type="text" class="form-control" name="search"
                    placeholder="Cari berdasarkan nama atau tanggal pemeriksaan" value="{{ request('search') }}">
                <button class="btn btn-secondary" type="submit">Cari</button>
            </div>
        </form>
        @if ($diagnosas->isEmpty())
            <p>Tidak ada data pasien yang tersimpan.</p>
        @else
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">Nama</th>
                            <th class="text-center align-middle">Usia</th>
                            <th class="text-center align-middle">Tanggal Pemeriksaan</th>
                            <th class="text-center align-middle">Jenis Pemeriksaan</th>
                            <th class="text-center align-middle">Gambar</th>
                            <th class="text-center align-middle">Prediksi</th>
                            <th class="text-center align-middle">Confidence</th>
                            <th class="text-center align-middle">Kesimpulan</th>
                            <th class="text-center align-middle">Gambar Area Kanker</th>
                            <th class="text-center align-middle">Gambar Sebaran Kanker</th>
                            <th class="text-center align-middle">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($diagnosas as $diagnosa)
                            <tr>
                                <td>{{ $diagnosa->nama }}</td>
                                <td>{{ $diagnosa->usia }}</td>
                                <td>{{ $diagnosa->tanggal_pemeriksaan }}</td>
                                <td>{{ $diagnosa->jenis_pemeriksaan }}</td>
                                <td><img src="{{ asset('storage/' . $diagnosa->image_path) }}" class="img-thumbnail"
                                        style="max-width: 100px;"></td>
                                <td>{{ $diagnosa->prediction }}</td>
                                <td>{{ number_format($diagnosa->confidence, 2) }}%</td>
                                <td>{{ $diagnosa->diagnosa }}</td>
                                <td><img src="{{ asset('storage/' . $diagnosa->canvas_output_path) }}"
                                        class="img-thumbnail" style="max-width: 100px;"></td>
                                <td><img src="{{ asset('storage/' . $diagnosa->mask_canvas_path) }}" class="img-thumbnail"
                                        style="max-width: 100px;"></td>
                                <td>
                                    <a href="{{ route('diagnosas.show', $diagnosa->id) }}"
                                        class="btn btn-info d-block btn-sm">Lihat</a>
                                    <a href="{{ route('diagnosas.edit', $diagnosa->id) }}"
                                        class="btn btn-warning d-block mt-2 btn-sm">Edit</a>
                                    <form action="{{ route('diagnosas.destroy', $diagnosa->id) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-danger mt-2 d-block w-100 btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="row mt-5">
        @foreach ($diagnosas as $diagnosa)
            <div class="col-lg-4 col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">{{ $diagnosa->nama }}</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Usia:</strong> {{ $diagnosa->usia }}</p>
                        <p><strong>Tanggal Pemeriksaan:</strong> {{ $diagnosa->tanggal_pemeriksaan }}</p>
                        <p><strong>Jenis Pemeriksaan:</strong> {{ $diagnosa->jenis_pemeriksaan }}</p>
                        <p class="crop-text-1"><strong>Kesimpulan:</strong> {{ $diagnosa->diagnosa }}</p>
                        <a href="{{ route('diagnosas.show', $diagnosa->id) }}" class="btn btn-secondary">Lihat Detail</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const tanggalPemeriksaanInput = document.getElementById('tanggal_pemeriksaan');
            if (tanggalPemeriksaanInput) {
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const dd = String(today.getDate()).padStart(2, '0');
                const formattedDate = `${yyyy}-${mm}-${dd}`;
                tanggalPemeriksaanInput.value = formattedDate;
            }

            const imageInput = document.getElementById('image');
            if (imageInput) {
                imageInput.addEventListener('change', previewImage);
            }
        });

        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                if (output) {
                    output.src = reader.result;
                    output.style.display = 'block';
                }
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Anda yakin ingin menghapus data ini?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        @if (session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
@endsection
