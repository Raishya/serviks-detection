<!DOCTYPE html>
<html>

<head>
    <title>Cervical Cancer Detection</title>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="{{ asset('model_loader.js') }}"></script>
    <script src="{{ asset('predict.js') }}"></script>
</head>

<body>
    <h1>Cervical Cancer Detection</h1>
    <input type="file" id="imageUpload" accept="image/*" onchange="previewFile()">
    <img id="previewImage" src="" alt="Image preview..." style="display:none; width=60px; height=60px">
    <button onclick="predict(document.getElementById('previewImage'))">Predict</button>
    <p id="predictionResult"></p>
    <script>
        function previewFile() {
            const preview = document.getElementById('previewImage');
            const file = document.getElementById('imageUpload').files[0];
            const reader = new FileReader();
            reader.onloadend = () => {
                preview.src = reader.result;
                preview.style.display = 'block';
            }
            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        }
    </script>
</body>

</html>
