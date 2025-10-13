function ValidateUserProductos() {
  const rolInput = document.querySelector("#rol");
  const BtnAddCar = document.querySelectorAll("#btnAddCar");

  if (!rolInput) {
    return; // Si rol no existe , no hacemos nada
  }
  const rol = rolInput.value; // Obtenemos el valor del rol desde el input oculto

  if (!BtnAddCar) return; // Si no existe el botón, no hacemos nada

  BtnAddCar.forEach((element) => {
    element.addEventListener("click", function (e) {
      e.preventDefault(); // Prevenir el comportamiento por defecto del botón
      if (rol === "NoSessionStart") {
        alert(
          "No tienes una sesion iniciada aun, por favor registrate o inica sesion para poder comprar."
        );
        location.href = "/Perfumeria/Public/login.php"; // Redirigir a la página de inicio de sesión
      }
    });
  });
}

export default ValidateUserProductos;
