function SepoMexAPI() {
  // Cuando el usuario mete CP, autocompleta todo
  document
    .getElementById("codigo_postal")
    .addEventListener("blur", function () {
      const cp = this.value;
      fetch(`https://sepomex.icalialabs.com/api/v1/zip_codes?zip_code=${cp}`)
        .then((res) => res.json())
        .then((data) => {
          data.zip_codes.forEach((zip) => {
            const EstadosDOM = document.querySelectorAll("#estado");
            const estado = zip.d_estado;
            const municipio = zip.d_mnpio;
            console.log(estado);
            if (estado && municipio) {
              EstadosDOM.forEach((estadoDOM) => {});
              // Asignar el estado y municipio al formulario
              document.getElementById("estado").value = estado;
              document.getElementById("municipio").value = municipio;

              // Mostrar el municipio y estado en la consola (opcional)
              console.log(`Estado: ${estado}, Municipio: ${municipio}`);
            }
          });
        });
    });
}

export default SepoMexAPI;
