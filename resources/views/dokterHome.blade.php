@extends('layouts.app')
@section('custom-css')
    <style>
        li.satu {
            list-style-type: disc;
            padding-left: 1em;
            margin-left: 1em;
        }

        .accordion {
            background-color: #1f1e1d;
            color: #fac0b7;
        }
    </style>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <h2 class="fw-bold fs-1">Selamat Datang, {{ Auth::user()->name }}!</h2>
                        <hr>
                        <div class="container mt-2">
                            <h3>Petunjuk penggunaan model machine learning</h3>
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Penjelasan Umum
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <p>Dalam konteks model prediksi berbasis machine learning seperti yang kami
                                                gunakan, "confidence" mengacu pada tingkat keyakinan model terhadap hasil
                                                prediksi yang dibuatnya. Secara lebih spesifik:</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Confidence (Tingkat Kepercayaan)
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <ul>
                                                <li class="satu">Confidence (Tingkat Kepercayaan): Ini adalah ukuran
                                                    seberapa yakin model
                                                    bahwa prediksinya benar. Biasanya, ini diukur dalam bentuk probabilitas
                                                    yang berkisar antara 0 dan 1.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                            Penjelasan dalam Kode Kami
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse"
                                        aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <ul>
                                                <li class="satu">Model Kami mengeluarkan nilai antara 0 dan 1 untuk setiap
                                                    prediksi.
                                                    Nilai ini menunjukkan probabilitas atau keyakinan model bahwa gambar
                                                    yang diberikan adalah milik kelas tertentu.</li>
                                                <li class="satu">Jika prediksi mentah ('raw_prediction') adalah 0.8, ini
                                                    berarti model
                                                    80% yakin bahwa gambar tersebut milik kelas "Normal".</li>
                                                <li class="satu">Sebaliknya, jika prediksi mentah adalah 0.2, ini berarti
                                                    model 20% yakin
                                                    bahwa gambar tersebut milik kelas "Normal" dan 80% yakin bahwa gambar
                                                    tersebut milik kelas "Kanker Serviks".</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingFour">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseFour" aria-expanded="false"
                                            aria-controls="collapseFour">
                                            Implementasi dalam Kode
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <ul>
                                                <li class="satu"><strong>Prediksi Kanker Serviks:</strong> Jika nilai
                                                    prediksi mentah
                                                    kurang dari atau
                                                    sama dengan <strong> 0.5</strong>, model menganggap gambar tersebut
                                                    sebagai <strong>"Kanker
                                                        Serviks"</strong> dengan tingkat kepercayaan sebesar <strong>'(1 -
                                                        raw_prediction)'</strong>.
                                                    Misalnya, jika <strong>'raw_prediction' </strong> adalah
                                                    <strong>0.3</strong>, maka
                                                    tingkat
                                                    kepercayaan
                                                    bahwa gambar tersebut adalah "Kanker Serviks" adalah <strong>0.7 (atau
                                                        70%)</strong> .
                                                </li>
                                                <li class="satu"> <strong>Prediksi Normal:</strong> Jika nilai prediksi
                                                    mentah lebih
                                                    besar dari <strong>0.5</strong> , model
                                                    menganggap gambar tersebut sebagai <strong>"Normal"</strong>dengan
                                                    tingkat kepercayaan
                                                    sebesar <strong> 'raw_prediction'</strong>. Misalnya, jika
                                                    <strong> 'raw_prediction'</strong> adalah <strong>0.8</strong>,
                                                    maka tingkat kepercayaan bahwa gambar tersebut adalah
                                                    <strong>"Normal"</strong> adalah
                                                    <strong>0.8 (atau 80%)</strong>.
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingFive">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseFive" aria-expanded="false"
                                            aria-controls="collapseFive">
                                            Contoh
                                        </button>
                                    </h2>
                                    <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <ul>
                                                <li class="satu">Misalkan prediksi mentah (`raw_prediction`) adalah 0.3:
                                                    <ul>
                                                        <li>Prediksi: "Kanker Serviks"</li>
                                                        <li>Confidence: \( 1 - 0.3 = 0.7 \) atau 70%</li>
                                                    </ul>
                                                </li>
                                                <li class="satu">Misalkan prediksi mentah (`raw_prediction`) adalah 0.8:
                                                    <ul>
                                                        <li>Prediksi: "Normal"</li>
                                                        <li>Confidence: \( 0.8 \) atau 80%</li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingSix">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                            Kesimpulan
                                        </button>
                                    </h2>
                                    <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <ul>
                                                <li class="satu">Tingkat kepercayaan (`confidence`) memberikan informasi
                                                    tambahan tentang
                                                    seberapa yakin model terhadap prediksinya. Ini penting untuk
                                                    interpretasi hasil, terutama dalam aplikasi medis, di mana tingkat
                                                    keyakinan yang tinggi dapat menambah kepercayaan dalam pengambilan
                                                    keputusan.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
