function CrearProducto() {
  const BtnCrear = document.getElementById("btnCrearProducto");
  if (!BtnCrear) return;

  BtnCrear.addEventListener("click", async (e) => {
    e.preventDefault();

    const form = document.getElementById("formProducto");
    const errorContainer = document.querySelector("#loginErrors");

    const formData = new FormData(form); // ✅ Esto ya toma todos los inputs, incluyendo imágenes

    fetch("/Perfumeria/Admin/AdminAccionesAPI/crear.php", {
      method: "POST",
      body: formData,
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
            "/Perfumeria/Admin/vistas/dashboard.php?id=exitoprenda";
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

export default CrearProducto;
