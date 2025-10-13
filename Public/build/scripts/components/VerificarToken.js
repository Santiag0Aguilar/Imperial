function VerificarToken() {
  const form = document.getElementById("FormVerificarToken");
  const BtnVerificarToken = document.getElementById("BtnVerificarToken");
  const errorContainer = document.getElementById("errorContainer");

  if (!form || !BtnVerificarToken || !errorContainer) {
    return;
  }
  BtnVerificarToken.addEventListener("click", async (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    try {
      const response = await fetch(
        "/Perfumeria/API/account/VerificarTokenAPI.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      const result = await response.json();

      if (result.success) {
        // ✅ Redirigir al formulario de cambio de contraseña
        window.location.href = "/Perfumeria/Public/account/CambiarPassword.php";
      } else {
        errorContainer.innerHTML = result.errors
          .map((err) => `<p class="Error">${err}</p>`)
          .join("");
        setTimeout(() => (errorContainer.innerHTML = ""), 5000);
      }
    } catch (error) {
      console.error("Error en la solicitud:", error.message);
      errorContainer.innerHTML = `<p class="Error">Error de conexión con el servidor.</p>`;
    }
  });
}

export default VerificarToken;
