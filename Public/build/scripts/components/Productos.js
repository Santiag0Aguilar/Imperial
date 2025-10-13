function FilterActiveMobile() {
  const BtnAtiveFilter = document.querySelector(".filter__active");
  const Filter = document.querySelector(".filtros");
  const Body = document.querySelector("body");

  Body.addEventListener("click", (e) => {
    if (e.target !== BtnAtiveFilter && !Filter.contains(e.target)) {
      Filter.classList.remove("filtros--active");
    }
  });
  BtnAtiveFilter.addEventListener("click", () => {
    Filter.classList.toggle("filtros--active");
    console.log(Filter);
  });
}

function InitMeter() {
  const slider = document.getElementById("slider-precio");
  const rangoPrecio = document.getElementById("rango-precio");

  noUiSlider.create(slider, {
    start: [1000, 6000],
    connect: true,
    range: {
      min: 0,
      max: 8000,
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

  // Filtrado al hacer clic
  document.getElementById("aplicar-filtro").addEventListener("click", () => {
    const generos = [
      ...document.querySelectorAll('input[name="genero"]:checked'),
    ].map((g) => g.value);
    const marca = document.getElementById("marca").value;
    const [min, max] = slider.noUiSlider
      .get()
      .map((v) => parseFloat(v.replace("$", "")));

    // 👉 Aquí puedes hacer una petición GET o POST al backend
    const filtros = {
      genero: generos,
      marca,
      precio_min: min,
      precio_max: max,
    };

    console.log("Filtros aplicados:", filtros);

    // fetch("/ruta-api", { method: "POST", body: JSON.stringify(filtros) })...
    // O redirigir con GET: ?genero=Hombre&marca=Dior&precio_min=100&precio_max=5000
  });
}
