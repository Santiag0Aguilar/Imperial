function AlertasApi() {
  const parmetros = new URLSearchParams(window.location.search);
  const id = parmetros.get("id");
  const contenedor = document.querySelector("body");
  const Alertas = document.createElement("div");
  Alertas.classList.add("alertas");

  if (id == "exito1") {
    console.log("Sesion iniciada exitosamente");
    contenedor.appendChild(Alertas);
    Alertas.innerHTML = `
    <div class="alerta">
    <h2>Sesion iniciada exitosamente</h2>
    <p>Bienvenido a ImperiumParfum</p>
    </div>
    `;
    setTimeout(() => {
      Alertas.remove();
      // Limpia la URL sin recargar
      const url = new URL(window.location);
      url.searchParams.delete("id");
      window.history.replaceState({}, document.title, url);
    }, 5000);
  } else if (id == "exito2") {
    console.log("Registro exitoso");
    contenedor.appendChild(Alertas);
    Alertas.innerHTML = `
    <div class="alerta">
    <h2>Registro exitoso</h2>
    <p>Bienvenido a ImperiumParfum</p>
    </div>
    `;
    setTimeout(() => {
      Alertas.remove();
      // Limpia la URL sin recargar
      const url = new URL(window.location);
      url.searchParams.delete("id");
      window.history.replaceState({}, document.title, url);
    }, 5000);
  }
}

export default AlertasApi;
