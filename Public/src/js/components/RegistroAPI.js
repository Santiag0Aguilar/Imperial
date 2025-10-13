function RegistroAPI() {
  const BtnRegistro = document.querySelector("#btnRegistro");
  if (!BtnRegistro) {
    return;
  }
  BtnRegistro.addEventListener("click", async function (e) {
    e.preventDefault();
    const nombre = document.querySelector("#nombre").value;
    const email = document.querySelector("#email").value;
    const telefono = document.querySelector("#telefono").value;
    const password = document.querySelector("#password").value;
    const rol = "cliente";
    const csrf_token = document.querySelector("input[name='csrf_token']").value;
    const errorContainer = document.querySelector("#loginErrors");

    const response = await fetch("/Perfumeria/API/acciones/registro.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        nombre,
        email,
        telefono,
        password,
        csrf_token,
        rol,
      }),
    });

    const data = await response.json();
    console.log(data.success);

    if (data.success) {
      window.location.href = "/Perfumeria/Public/index.php?id=exito2";
    } else {
      const errors = data.errors || [
        data.message || "Ocurrió un error desconocido.",
      ];
      errorContainer.innerHTML = errors
        .map((err) => `<p class="Error">${err}</p>`)
        .join("");
      setTimeout(() => {
        errorContainer.innerHTML = "";
      }, 10000);
    }
  });
}

export default RegistroAPI;
