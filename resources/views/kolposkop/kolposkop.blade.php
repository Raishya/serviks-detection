@extends('layouts.app')
@section('custom-css')
    <style>
        #cameraPreview {
            width: 100%;
            height: auto;
            /* transform: scaleX(-1); */ /* Flip the video horizontally - di-nonaktifkan agar tidak mirror */
        }

        #canvas {
            width: 100%;
            height: auto;
            display: none;
        }

        #cameraError {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Live Camera Preview</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center">
                        <video id="cameraPreview" autoplay></video>
                        <canvas id="canvas"></canvas>
                        <div id="cameraError" class="alert alert-danger mt-3">Akses kamera ditolak. Harap izinkan akses kamera
                            pada pengaturan browser Anda.</div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button id="takePhoto" class="btn btn-primary">Take Photo</button>
                    <button id="retakePhoto" class="btn btn-secondary d-none">Retake Photo</button>
                    <button id="savePhoto" class="btn btn-success d-none">Simpan dan Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('cameraPreview');
            const canvas = document.getElementById('canvas');
            const takePhotoButton = document.getElementById('takePhoto');
            const retakePhotoButton = document.getElementById('retakePhoto');
            const savePhotoButton = document.getElementById('savePhoto');
            const cameraError = document.getElementById('cameraError');

            // Function to check if browser is Firefox
            function isFirefox() {
                return typeof InstallTrigger !== 'undefined';
            }

            // Function to start camera
            async function startCamera() {
                try {
                    let stream;

                    if (isFirefox()) {
                        const devices = await navigator.mediaDevices.enumerateDevices();
                        const videoDevice = devices.find(device => device.kind === 'videoinput');

                        stream = await navigator.mediaDevices.getUserMedia({
                            video: {
                                deviceId: videoDevice ? videoDevice.deviceId : undefined
                            }
                        });
                    } else {
                        stream = await navigator.mediaDevices.getUserMedia({
                            video: true
                        });
                    }

                    video.srcObject = stream;
                    video.play();
                } catch (error) {
                    cameraError.style.display = 'block';
                    console.error('Error accessing camera: ', error);
                }
            }

            // Start camera on page load
            startCamera();

            // Take photo
            takePhotoButton.addEventListener('click', function() {
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                // context.translate(video.videoWidth, 0); // Flip the image back horizontally (di-nonaktifkan)
                // context.scale(-1, 1); // di-nonaktifkan
                context.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);

                const dataURL = canvas.toDataURL('image/png');
                savePhotoButton.classList.remove('d-none');
                retakePhotoButton.classList.remove('d-none');
                takePhotoButton.classList.add('d-none');

                // Display the screenshot in the video element
                video.style.display = 'none';
                canvas.style.display = 'block';

                savePhotoButton.onclick = function() {
                    fetch('{{ route('kolposkop.saveImage') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            image: dataURL
                        })
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            window.location.href = "{{ route('addPasien') }}";
                        } else {
                            alert('Gagal menyimpan gambar. Silakan coba lagi.');
                        }
                    }).catch(error => console.error('Error:', error));
                };
            });

            // Retake photo
            retakePhotoButton.addEventListener('click', function() {
                canvas.style.display = 'none';
                video.style.display = 'block';
                savePhotoButton.classList.add('d-none');
                retakePhotoButton.classList.add('d-none');
                takePhotoButton.classList.remove('d-none');
            });
        });
    </script>
@endsection
