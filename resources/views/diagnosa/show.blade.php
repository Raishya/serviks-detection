@extends('layouts.app')
@section('custom-css')
    <style>

    </style>
@endsection

@section('content')
    <div class="container">
        <h1>Detail Data Pasien</h1>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th scope="row">Nama</th>
                            <td>{{ $diagnosa->nama }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Usia</th>
                            <td>{{ $diagnosa->usia }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Tanggal Pemeriksaan</th>
                            <td>{{ $diagnosa->tanggal_pemeriksaan }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Jenis Pemeriksaan</th>
                            <td>{{ $diagnosa->jenis_pemeriksaan }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Gambar</th>
                            <td>
                                <img src="{{ asset('storage/' . $diagnosa->image_path) }}" class="img-thumbnail"
                                    style="max-width: 200px;">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Prediksi</th>
                            <td>{{ $diagnosa->prediction }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Confidence</th>
                            <td>{{ $diagnosa->confidence * 100 }}%</td>
                        </tr>
                        <tr>
                            <th scope="row">>Gambar Area Kanker</th>
                            <td>
                                <img src="{{ asset('storage/' . $diagnosa->canvas_output_path) }}" class="img-thumbnail"
                                    style="max-width: 200px;">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Gambar Sebaran Kanker</th>
                            <td>
                                <img src="{{ asset('storage/' . $diagnosa->mask_canvas_path) }}" class="img-thumbnail"
                                    style="max-width: 200px;">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Kesimpulan</th>
                            <td>{{ $diagnosa->diagnosa }}</td>
                        </tr>
                    </tbody>
                </table>

                <a href="{{ route('diagnosas.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
@endsection
