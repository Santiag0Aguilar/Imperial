function DeleteOneCar() {
  const BtnDeleteProducto = document.querySelectorAll(".BtnDeleteProducto");
  if (BtnDeleteProducto.length === 0) {
    return;
  }
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
      }).then(async (res) => {
        const text = await res.text();
        try {
          const json = JSON.parse(text);
          console.log("Respuesta del servidor:", json);
          return json;
        } catch (e) {
          console.error("No es JSON:", text);
          throw new Error("Respuesta inválida del servidor");
        }
      });
    });
  });
}

export default DeleteOneCar;
