function FormDireccion() {
  const Form = document.querySelector("#formulario_direccion-Pago");

  Form.addEventListener("submit", (e) => {
    e.preventDefault();
    console.log("Enviando datos al servidor...");

    const formData = new FormData(Form);

    // Mostrar datos que se están enviando
    console.log("Datos enviados:");
    for (const [key, value] of formData.entries()) {
      console.log(`${key}: ${value}`);
    }

    fetch("/Perfumeria/API/pago/GuardarDireccion.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Error en la respuesta del servidor");
        } else {
          console.log("Datos enviados correctamente.");
        }
        return response.json();
      })
      .then((data) => {
        if (data.success) {
          Swal.fire({
            icon: "success",
            title: "Dirección guardada",
            text: "Tu dirección se ha registrado correctamente.",
            color: "#000000",
            confirmButtonColor: "#001f3f",
          });

          document.querySelector("#formulario_direccion-Pago").innerHTML = "";
        } else if (data.error) {
          switch (data.error) {
            case "No se encontró un pedido pendiente.":
              Swal.fire({
                icon: "error",
                title: "Pedido no encontrado",
                text: "No se encontró un pedido pendiente para guardar la dirección.",
              });
              break;
            case "Faltan datos.":
              Swal.fire({
                icon: "error",
                title: "Datos incompletos",
                text: "Por favor, completa todos los campos requeridos.",
              });
              break;
            case "Usuario no autenticado.":
              Swal.fire({
                icon: "error",
                title: "Sesión expirada",
                text: "Debes iniciar sesión nuevamente.",
              });
              break;
            case "Ya existe una dirección registrada para este pedido.":
              Swal.fire({
                icon: "info",
                title: "Dirección existente",
                text: "Ya registraste una dirección para este pedido.",
              });
              document.querySelector("#formulario_direccion-Pago").innerHTML =
                "";
              break;
            default:
              Swal.fire({
                icon: "error",
                title: "Error desconocido",
                text: data.error,
              });
              break;
          }
        } else {
          Swal.fire({
            icon: "error",
            title: "Error inesperado",
            text: "Algo salió mal. Intenta nuevamente.",
          });
        }
      });
  });
}

export default FormDireccion;
