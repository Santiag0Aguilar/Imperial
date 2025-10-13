function DeleteCar() {
  const BtnDelete = document.querySelector(".BtnDeleteCar");
  const BtnDeleteProducto = document.querySelectorAll(".BtnDeleteProducto");

  BtnDelete.addEventListener("click", function (e) {
    e.preventDefault();
    const IdUsuario = BtnDelete.dataset.idusuario;

    // Enviar un objeto, no solo el string
    const payload = { usuario_id: parseInt(IdUsuario) };

    console.log("Enviando a PHP:", payload);

    fetch("/Perfumeria/API/carrito/VaciarAll_carrito.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    })
      .then((res) => res.json())
      .then((data) => {
        console.log("Respuesta del servidor:", data);
      })
      .catch((err) => console.error("Error en fetch:", err));
  });
  BtnDeleteProducto.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const IdProducto = btn.dataset.idproducto;
      const IdUsuario = btn.dataset.idusuario;

      // Enviar un objeto, no solo el string
      const payload = {
        producto_id: parseInt(IdProducto),
        usuario_id: parseInt(IdUsuario),
      };

      console.log("Enviando a PHP:", payload);

      fetch("/Perfumeria/API/carrito/BorrarProducto.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      })
        .then((res) => res.json())
        .then((data) => {
          console.log("Respuesta del servidor:", data);
          // Aquí podrías actualizar la UI para reflejar el cambio
        })
        .catch((err) => console.error("Error en fetch:", err));
    });
  });
}

export default DeleteCar;
