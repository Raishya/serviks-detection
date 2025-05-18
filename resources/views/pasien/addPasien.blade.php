@extends('layouts.app')
@section('custom-css')
    <style>
        :root {
            --primary: #fac0b7;
            --secondary: #1f1e1d;
            --accent: #e52a71;
        }

        .tambah-pasien {
            background-color: var(--secondary);
            color: var(--primary);
            padding: 2rem;
            border: 2px solid var(--accent);
            border-radius: 15px;
        }
    </style>
@endsection
@section('content')
    <div class="container">

        <h1 class="fs-1">Tambah Pasien</h1>
        <form action="{{ route('savePasien') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 tambah-pasien shadow-lg">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="usia" class="form-label">Usia</label>
                        <input type="number" class="form-control" id="usia" name="usia" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_pemeriksaan" class="form-label">Tanggal Pemeriksaan</label>
                        <input type="date" class="form-control" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="jenis_pemeriksaan" class="form-label">Jenis Pemeriksaan</label>
                        <input type="text" class="form-control" id="jenis_pemeriksaan" name="jenis_pemeriksaan" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4 w-100">Next</button>
        </form>
    </div>
    </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            // Ambil elemen input tanggal
            const tanggalPemeriksaanInput = document.getElementById('tanggal_pemeriksaan');
            // Buat objek tanggal untuk hari ini
            const today = new Date();
            // Format tanggal menjadi YYYY-MM-DD
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            const formattedDate = `${yyyy}-${mm}-${dd}`;
            // Set nilai default input tanggal
            tanggalPemeriksaanInput.value = formattedDate;
        });
    </script>
@endsection
