function InitMeter() {
  const slider = document.getElementById("slider-precio");
  const rangoPrecio = document.getElementById("rango-precio");
  if (!slider || !rangoPrecio || !window.noUiSlider) return;

  noUiSlider.create(slider, {
    start: [0, 8000],
    connect: true,
    range: {
      min: 0,
      max: 10000,
    },
    tooltips: [true, true],
    format: {
      to: (value) => `$${parseFloat(value).toFixed(2)}`,
      from: (value) => Number(value.replace("$", "")),
    },
  });

  slider.noUiSlider.on("update", (values) => {
    rangoPrecio.innerText = values.join(" - ");
  });
}

export default InitMeter;
