@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Rekam Medis Saya</h1>

        <form method="GET" action="{{ route('rekam-medis', ['user_id' => Auth::user()->id]) }}">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ request('nama') }}">
            </div>
            <div class="mb-3">
                <label for="tanggal_pemeriksaan" class="form-label">Tanggal Pemeriksaan</label>
                <input type="date" class="form-control" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan"
                    value="{{ request('tanggal_pemeriksaan') }}">
            </div>
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>

        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (isset($diagnosas))
            @if ($diagnosas->isEmpty())
                <div class="alert alert-warning mt-3">Tidak ada data yang ditemukan.</div>
            @else
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Tanggal Pemeriksaan</th>
                            <th>Usia</th>
                            <th>Jenis Pemeriksaan</th>
                            <th>Prediksi</th>
                            <th>Confidence</th>
                            <th>Diagnosa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($diagnosas as $item)
                            <tr>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->tanggal_pemeriksaan }}</td>
                                <td>{{ $item->usia }}</td>
                                <td>{{ $item->jenis_pemeriksaan }}</td>
                                <td>{{ $item->prediction }}</td>
                                <td>{{ number_format($item->confidence, 2) }}%</td>
                                <td>{{ $item->diagnosa }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endif
    </div>
@endsection
