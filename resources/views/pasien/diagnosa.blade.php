@extends('layouts.app')

@section('custom-css')
<script>
  function onOpenCvReady() {
    console.log( 'OpenCV Ready', cv);
  }
</script>
    <script async src="https://docs.opencv.org/4.5.0/opencv.js" onload="onOpenCvReady();"></script>

    <style>
        :root {
            --primary: #fac0b7;
            --secondary: #1f1e1d;
            --accent: #e52a71;
        }

        .diagnosa-form {
            border-radius: 15px;
            padding: 2rem;
            background-color: var(--secondary);
        }

        .diagnosa-form h1,
        h3 {
            color: var(--accent);
        }

        .diagnosa-form p,
        label {
            color: var(--primary);
        }

        hr {
            border-radius: 10px;
            width: 100%;
            height: 0.5rem;
            background-color: var(--accent);
            opacity: 1;
        }

        .hasilPrediksi {
            width: 30vw;
            border-radius: 10px;
            padding: 2rem;
            background-color: var(--primary);
        }

        p.hasilPrediksi {
            color: #1f1e1d;
        }

        .loading {
            display: none;
            flex-direction: column;
            align-items: center;
        }

        .loader {
            width: 120px;
            height: 22px;
            border-radius: 20px;
            color: var(--primary);
            border: 2px solid;
            position: relative;
            overflow: hidden;
        }

        .loader::before {
            content: "";
            position: absolute;
            margin: 2px;
            inset: 0 100% 0 0;
            border-radius: inherit;
            background: currentColor;
            animation: l6 2s infinite;
        }

        .loader.completed::before {
            animation: none;
            inset: 0;
        }

        @keyframes l6 {
            100% {
                inset: 0;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container diagnosa-form">
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <h1>Halaman Diagnosa</h1>
        <hr>
        @if ($data)
            <table class="table table-striped table-bordered">
                <tbody>
                    <tr>
                        <th scope="row">Nama Lengkap</th>
                        <td>{{ $data['nama'] }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Usia</th>
                        <td>{{ $data['usia'] }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Tanggal Pemeriksaan</th>
                        <td>{{ $data['tanggal_pemeriksaan'] }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Jenis Pemeriksaan</th>
                        <td>{{ $data['jenis_pemeriksaan'] }}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <p>Data pasien tidak tersedia. Silakan tambahkan pasien terlebih dahulu.</p>
        @endif
        <hr>

        <form id="diagnosaForm" method="POST" enctype="multipart/form-data" action="{{ route('diagnosa.save') }}">
            @csrf
            <input type="hidden" id="nama" name="nama" value="{{ $data['nama'] ?? '' }}">
            <input type="hidden" id="usia" name="usia" value="{{ $data['usia'] ?? '' }}">
            <input type="hidden" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan"
                value="{{ $data['tanggal_pemeriksaan'] ?? '' }}">
            <input type="hidden" id="jenis_pemeriksaan" name="jenis_pemeriksaan"
                value="{{ $data['jenis_pemeriksaan'] ?? '' }}">



            <div class="mb-3">
                <label for="image" class="form-label">Upload Gambar Citra:</label>
                <input class="form-control" type="file" id="image" name="image" onchange="previewImage(event)"
                    required>
            </div>
            <div class="mb-3">
                @if ($imagePath)
                    <img id="preview" src="{{ asset('storage/' . $imagePath) }}" class="img-thumbnail"
                        alt="Gambar yang diupload" style="max-width: 300px;">
                @else
                    <img id="preview" class="img-thumbnail" style="max-width: 300px; display: none;">
                @endif
            </div>
            <button type="button" class="btn btn-primary" onclick="predictImage()">Prediksi</button>
        </form>

        <div id="loadingSpinner" class="fixed inset-0 flex items-center justify-center bg-gray-100 bg-opacity-75 loading">
            <div class="loader"></div>
            <p id="loadingTimer" class="text-gray-700 mt-2">Loading model... <span id="timeElapsed">0</span>s</p>
            <p id="loadingPercentage" class="text-gray-700 mt-2">Loaded: <span id="percentageLoaded">0</span>%</p>
        </div>
        <div id="canvasKanker" class="container py-5" style="display: none;">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <h3>Gambar Area Kanker</h3>
                    <canvas id="canvasOutput" class="mb-5"></canvas>
                </div>
                <div class="col-lg-6 col-md-12">
                    <h3>Gambar Sebaran Kanker</h3>
                    <canvas id="maskCanvas"></canvas>
                </div>
            </div>
        </div>
        <div id="predictionResult" class="mt-3 col-lg-6 col-md-12"
            style="{{ $result ? 'display:block;' : 'display:none;' }}">
            <h3>Hasil Prediksi:</h3>
            <p id="predictionText" class="hasilPrediksi text-center fw-bold w-100">
                {{ $result ? 'Prediksi: ' . $result['prediction'] . ' dengan confidence: ' . $result['confidence'] . '%' : '' }}
            </p>

            <form id="saveForm" action="{{ route('diagnosa.save') }}" method="POST">
                @csrf
                <input type="hidden" id="prediction" name="prediction" value="{{ $result ? json_encode($result) : '' }}">
                <div class="mb-3">
                    <label for="diagnosa" class="form-label">Kesimpulan:</label>
                    <textarea class="form-control" id="diagnosa" name="diagnosa" rows="3" required></textarea>
                </div>
                <input type="hidden" id="canvasOutputPath" name="canvas_output_path"
                    value="{{ $canvasOutputPath ?? '' }}">
                <input type="hidden" id="maskCanvasPath" name="mask_canvas_path" value="{{ $maskCanvasPath ?? '' }}">

                <button type="submit" class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>

    <script>
        let model;
        let startTime;
        let timerInterval;
        let percentageLoaded = 0;

        async function loadModel() {
            document.getElementById('loadingSpinner').style.display = 'flex';
            startTime = Date.now();
            updateTimer();

            try {
                model = await tf.loadLayersModel('/model_js/model.json', {
                    onProgress: (fraction) => {
                        percentageLoaded = Math.round(fraction * 100);
                        document.getElementById('percentageLoaded').textContent = percentageLoaded;

                        if (percentageLoaded === 100) {
                            clearInterval(timerInterval);
                            document.querySelector('.loader').classList.add('completed');
                            const elapsedTime = Math.floor((Date.now() - startTime) / 1000);
                            document.getElementById('loadingTimer').textContent =
                                `Model berhasil dimuat dalam: ${elapsedTime}s`;
                            Swal.fire({
                                icon: 'success',
                                title: 'Model berhasil dimuat',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                });
                console.log('Model loaded successfully');
            } catch (error) {
                console.error('Error loading model:', error);
                alert('Gagal memuat model. Silakan coba lagi nanti.');
            }
        }

        function updateTimer() {
            timerInterval = setInterval(() => {
                const timeElapsed = Math.floor((Date.now() - startTime) / 1000);
                document.getElementById('timeElapsed').textContent = timeElapsed;
            }, 1000);
        }

        function previewImage(event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function() {
                var preview = document.getElementById('preview');
                preview.src = reader.result;
                preview.style.display = 'block';

                // Save image path to session
                saveImagePath(input.files[0]);
            };
            reader.readAsDataURL(input.files[0]);
        }

        function saveImagePath(file) {
            const formData = new FormData();
            formData.append('image', file);

            fetch('/save-image-path', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Image path saved:', data.imagePath);
                    // Optionally update UI or session data as needed
                })
                .catch(error => {
                    console.error('Error saving image path:', error);
                });
        }

        async function saveCanvasImages() {
            const canvasOutput = document.getElementById('canvasOutput');
            const maskCanvas = document.getElementById('maskCanvas');

            const canvasOutputDataUrl = canvasOutput.toDataURL('image/png');
            const maskCanvasDataUrl = maskCanvas.toDataURL('image/png');

            const formData = new FormData();
            formData.append('canvasOutput', canvasOutputDataUrl);
            formData.append('maskCanvas', maskCanvasDataUrl);

            try {
                const response = await fetch('/save-canvas-images', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    console.log('Images saved successfully');
                    // Update hidden inputs with image paths
                    document.getElementById('canvasOutputPath').value = data.canvasOutputPath;
                    document.getElementById('maskCanvasPath').value = data.maskCanvasPath;
                    console.log('canvasOutputPath:', data.canvasOutputPath); // Logging output image URL
                    console.log('maskCanvasPath:', data.maskCanvasPath); // Logging mask image URL

                } else {
                    console.error('Failed to save images', data.error);
                }
            } catch (error) {
                console.error('Error saving images:', error);
            }
        }
        async function predictImage() {
            if (!model) {
                alert('Model belum dimuat. Silakan tunggu beberapa saat dan coba lagi.');
                return;
            }

            const imageElement = document.getElementById('preview');
            const tensor = tf.browser.fromPixels(imageElement)
                .resizeNearestNeighbor([224, 224]) // Resize to 224x224 as expected by the model
                .toFloat()
                .expandDims()
                .div(255.0); // Normalization

            const predictions = await model.predict(tensor).data();

            console.log('Predictions:', predictions);

            if (predictions.length === 0) {
                alert('Model tidak menghasilkan prediksi yang diharapkan.');
                return;
            }

            let rawPrediction = predictions[0];
            let label = rawPrediction > 0.5 ? 'Normal' : 'Kanker Serviks';
            let confidence = rawPrediction > 0.5 ?
                (rawPrediction * 100).toFixed(2) :
                ((1 - rawPrediction) * 100).toFixed(2);

            if (isNaN(confidence)) {
                console.error('Confidence value is NaN:', confidence);
                confidence = 0;
            }

            const predictionText = `Prediksi: ${label} dengan confidence: ${confidence}%`;
            document.getElementById('predictionText').textContent = predictionText;

            document.getElementById('prediction').value = JSON.stringify({
                prediction: label,
                confidence: confidence
            });

            // Update the diagnosis textarea based on the prediction
            const diagnosaTextarea = document.getElementById('diagnosa');
            if (label === 'Kanker Serviks') {
                diagnosaTextarea.value = 'perlu pemeriksaan lebih lanjut.';
            } else if (label === 'Normal') {
                diagnosaTextarea.value = 'tidak terindikasi kanker.';
            }

            if (label === 'Kanker Serviks') {
                // Process the image to detect red percentage and draw bounding box
                const src = cv.imread(imageElement);
                const {
                    maskCleaned,
                    contours,
                    redPercentage
                } = detectRedPercentage(src);

                // Display mask for debugging
                cv.imshow('maskCanvas', maskCleaned);
                // Draw the largest bounding box
                const result = drawLargestBoundingBox(src, contours);
                cv.imshow('canvasOutput', result.frame);
            } else {
                // Display placeholder
                displayPlaceholder(imageElement);
            }

            await saveCanvasImages();
            document.getElementById('canvasKanker').style.display = 'block';
            document.getElementById('loadingSpinner').style.display = 'none';
            document.getElementById('predictionResult').style.display = 'block';
        }

        function displayPlaceholder(imageElement) {
            // Create a black mask
            const maskCanvas = document.getElementById('maskCanvas');
            const ctxMask = maskCanvas.getContext('2d');
            ctxMask.fillStyle = 'black';
            ctxMask.fillRect(0, 0, maskCanvas.width, maskCanvas.height);

            // Display original image without bounding box
            const canvasOutput = document.getElementById('canvasOutput');
            const ctxOutput = canvasOutput.getContext('2d');
            ctxOutput.drawImage(imageElement, 0, 0, canvasOutput.width, canvasOutput.height);
        }

        function detectRedPercentage(frame) {
            const hsvFrame = new cv.Mat();
            cv.cvtColor(frame, hsvFrame, cv.COLOR_BGR2HSV);


            const lowerRed = new cv.Mat(hsvFrame.rows, hsvFrame.cols, hsvFrame.type(), [120, 0, 0, 0]);
            const upperRed = new cv.Mat(hsvFrame.rows, hsvFrame.cols, hsvFrame.type(), [130, 255, 255, 255]);
            const lowerRed2 = new cv.Mat(hsvFrame.rows, hsvFrame.cols, hsvFrame.type(), [0, 0, 0, 0]);
            const upperRed2 = new cv.Mat(hsvFrame.rows, hsvFrame.cols, hsvFrame.type(), [0, 0, 0, 0]);

            const maskRed1 = new cv.Mat();
            const maskRed2 = new cv.Mat();
            cv.inRange(hsvFrame, lowerRed, upperRed, maskRed1);
            cv.inRange(hsvFrame, lowerRed2, upperRed2, maskRed2);

            const maskRed = new cv.Mat();
            cv.add(maskRed1, maskRed2, maskRed);

            const kernel = cv.Mat.ones(5, 5, cv.CV_8U);
            const maskCleaned = new cv.Mat();
            cv.morphologyEx(maskRed, maskCleaned, cv.MORPH_CLOSE, kernel);

            const contours = new cv.MatVector();
            const hierarchy = new cv.Mat();
            cv.findContours(maskCleaned, contours, hierarchy, cv.RETR_EXTERNAL, cv.CHAIN_APPROX_SIMPLE);

            console.log(`Number of contours found: ${contours.size()}`);

            const redArea = cv.countNonZero(maskCleaned);
            const totalArea = maskCleaned.rows * maskCleaned.cols;
            const redPercentage = (redArea / totalArea) * 100;

            lowerRed.delete();
            upperRed.delete();
            lowerRed2.delete();
            upperRed2.delete();
            hsvFrame.delete();
            kernel.delete();
            maskRed1.delete();
            maskRed2.delete();
            maskRed.delete();
            hierarchy.delete();

            return {
                maskCleaned,
                contours,
                redPercentage
            };
        }

        function drawLargestBoundingBox(frame, contours) {
            if (contours.size() === 0) {
                console.log('No contours found');
                return {
                    frame,
                    redPercentage: 0
                };
            }

            const largestContour = contours.get(0);
            let maxArea = cv.contourArea(largestContour);
            let largestContourIndex = 0;

            for (let i = 1; i < contours.size(); i++) {
                const contour = contours.get(i);
                const area = cv.contourArea(contour);
                if (area > maxArea) {
                    maxArea = area;
                    largestContourIndex = i;
                }
            }

            const boundingRect = cv.boundingRect(contours.get(largestContourIndex));
            const point1 = new cv.Point(boundingRect.x, boundingRect.y);
            const point2 = new cv.Point(boundingRect.x + boundingRect.width, boundingRect.y + boundingRect.height);

            console.log(`Bounding Box: (${point1.x}, ${point1.y}), (${point2.x}, ${point2.y})`);

            cv.rectangle(frame, point1, point2, [255, 0, 0, 255], 2);
            cv.drawContours(frame, contours, largestContourIndex, [0, 0, 255, 255], 1);

            const redArea = cv.contourArea(contours.get(largestContourIndex));
            const totalArea = frame.rows * frame.cols;
            const redPercentage = (redArea / totalArea) * 100;

            cv.putText(frame, `abnormal`, point1, cv.FONT_HERSHEY_SIMPLEX, 0.5, [255, 0, 0, 255], 2);

            return {
                frame,
                redPercentage
            };

        }

        // Muat model saat halaman di-load
        window.addEventListener('load', loadModel);
    </script>
@endsection
