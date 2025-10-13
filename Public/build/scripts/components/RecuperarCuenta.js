function recuperarCuenta() {
  const BtnRecuperarCuenta = document.querySelector("#btnRecuperarCuenta");
  if (!BtnRecuperarCuenta) return;
  BtnRecuperarCuenta.addEventListener("click", async (e) => {
    e.preventDefault();
    const form = document.getElementById("formRecuperarCuenta");
    const formData = new FormData(form); // ✅ Esto ya toma todos los inputs, incluyendo imágenes

    console.log(
      "Datos del formulario:",
      Object.fromEntries(formData.entries())
    );
    fetch("/Perfumeria/API/account/RecuperarCuentaAPI.php", {
      method: "POST",
      body: formData, // ✅ Aquí mandas FormData como debe ser
    })
      .then(async (response) => {
        const contentType = response.headers.get("content-type");
        if (contentType && contentType.includes("application/json")) {
          return response.json();
        } else {
          const text = await response.text();
          throw new Error("Respuesta no JSON del servidor: " + text);
        }
      })
      .then((data) => {
        if (data.success) {
          window.location.href =
            "/Perfumeria/Public/account/VerificarToken.php";
        } else {
          const errors = data.errors || [
            data.message || "Ocurrió un error desconocido.",
          ];
          errorContainer.innerHTML = errors
            .map((err) => `<p class="Error">${err}</p>`)
            .join("");
          setTimeout(() => {
            errorContainer.innerHTML = "";
          }, 5000);
        }
      })
      .catch((error) => console.error("Error en la solicitud:", error.message));
  });
}

export default recuperarCuenta;
