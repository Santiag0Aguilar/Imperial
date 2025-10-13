function BorrarAPI() {
  const forms = document.querySelectorAll(".formEliminar");
  if (!forms.length) return;

  forms.forEach((form) => {
    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      console.log("Botón de eliminar presionado");

      const id = form.querySelector("input[name='id']").value;
      const csrf_token = form.querySelector("input[name='csrf_token']").value;

      const response = await fetch(
        "/Perfumeria/Admin/AdminAccionesAPI/borrar.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ id, csrf_token }),
        }
      );

      const data = await response.json();
      if (data.success) {
        alert(data.message);
        location.reload();
      } else {
        alert(data.errors.join("\n"));
      }
    });
  });
}

export default BorrarAPI;
