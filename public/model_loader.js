// model_loader.js
async function loadModel() {
  const model = await tf.loadLayersModel('/model_js/model.json');
  return model;
}
