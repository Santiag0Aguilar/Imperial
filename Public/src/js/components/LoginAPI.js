function LoginAPI() {
  const BtnLogin = document.querySelector("#btnLogin");
  if (!BtnLogin) {
    return;
  }
  BtnLogin.addEventListener("click", async (e) => {
    e.preventDefault();

    const email = document.querySelector("#email").value;
    const password = document.querySelector("#password").value;
    const csrf_token = document.querySelector("input[name='csrf_token']").value;
    const errorContainer = document.querySelector("#loginErrors");

    const response = await fetch("/Perfumeria/API/acciones/login.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ email, password, csrf_token }),
    });

    const data = await response.json();
    console.log(data);
    if (data.success) {
      window.location.href = "/Perfumeria/Public/index.php?id=exito1";
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
  });
}

export default LoginAPI;
