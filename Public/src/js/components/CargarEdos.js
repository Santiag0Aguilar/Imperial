function CargarEdos() {
  fetch("/Perfumeria/estados.json")
    .then((response) => response.json())
    .then((data) => {
      console.log("Estados cargados:", data);
      poblarSelectEstados(data);
    });
}
function poblarSelectEstados(estados) {
  const selectEstado = document.getElementById("estado");
  if (!selectEstado) {
    console.error("El elemento select con id 'estado' no se encontró.");
    return;
  }
  estados.forEach((estado) => {
    const option = document.createElement("option");
    option.value = estado.id;
    option.textContent = estado.nombre;
    option.id = "estado";
    selectEstado.appendChild(option);
  });
}

export default CargarEdos;
