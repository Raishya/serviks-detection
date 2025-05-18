// predict.js
async function predict(imageElement) {
  const model = await loadModel();
  const tensor = tf.browser.fromPixels(imageElement).resizeNearestNeighbor([224, 224]).toFloat().expandDims();
  const prediction = model.predict(tensor);
  const result = (await prediction.data())[0];
  console.log(result > 0.5 ? 'Cancer Detected' : 'Normal');
  document.getElementById('predictionResult').innerText = result > 0.5 ? 'Cancer Detected' : 'Normal';
}
