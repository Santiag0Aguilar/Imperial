function AddMunicipios() {
  const MunicipioInput = document.querySelector("#municipio");
  const Estado = document.querySelector("#estado");
  let NombreEstado = "";

  Estado.addEventListener("change", (option) => {
    const OptionSelected = option.target.value;
    console.log(OptionSelected);
    CrearMunicipios(OptionSelected);
    MunicipioInput.innerHTML = ""; // Limpiar opciones previas
  });

  function CrearMunicipios(option) {
    fetch("/Perfumeria/estados.json")
      .then((response) => response.json())
      .then((data) => {
        const EstadoData = data.find((estado) => estado.id === option);
        NombreEstado = EstadoData.nombre;
      });

    fetch("/Perfumeria/estados-municipios.json")
      .then((response) => response.json())
      .then((data) => {
        const municipios = data[NombreEstado];

        municipios.forEach((municipio) => {
          const option = document.createElement("option");
          option.value = municipio;
          option.textContent = municipio;
          MunicipioInput.appendChild(option);
        });
      });
  }
}

export default AddMunicipios;
