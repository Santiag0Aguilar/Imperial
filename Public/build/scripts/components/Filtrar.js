function filtrar() {
  const aplicarFiltro = document.getElementById("aplicar-filtro");
  if (!aplicarFiltro) return; // Si no existe el botón, no hacemos nada

  aplicarFiltro.addEventListener("click", () => {
    const generos = Array.from(
      document.querySelectorAll('input[name="genero"]:checked')
    ).map((el) => el.value);
    const marca = document.getElementById("marca").value;
    const orden = document.getElementById("ordenar").value;
    const categoriaActual = document.querySelector("main").dataset.categoria;
    console.log(categoriaActual);

    const [precio_min, precio_max] = document
      .getElementById("slider-precio")
      .noUiSlider.get()
      .map((v) => parseFloat(v.replace("$", "")));

    const formData = new FormData();
    formData.append("generos", JSON.stringify(generos));
    formData.append("marca", marca);
    formData.append("orden", orden);
    formData.append("precio_min", precio_min);
    formData.append("precio_max", precio_max);
    formData.append("categoria", categoriaActual);

    fetch("/Perfumeria/API/filtrar/filtrar_acciones.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.text())
      .then((html) => {
        console.log(html);
        document.querySelector(".Perfumes__Container").innerHTML = html;
      });
  });
}

export default filtrar;
