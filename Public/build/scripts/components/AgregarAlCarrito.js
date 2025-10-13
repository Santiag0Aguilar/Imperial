function agregarAlCarrito() {
  const AddCarritoBtns = document.querySelectorAll(".btnAddCar");

  AddCarritoBtns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const producto = {
        id: btn.dataset.id,
        nombre: btn.dataset.nombre,
        precio: btn.dataset.precio,
        genero: btn.dataset.genero,
        marca: btn.dataset.marca,
        usuario_id: btn.dataset.idusuario,
        cantidad: 1,
      };

      fetch("/Perfumeria/API/carrito/agregar_carrito.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(producto),
      })
        .then((response) => {
          if (!response.ok) {
            if (response.status === 400) {
              mostrarMensaje(
                "Debes iniciar sesión o crear una cuenta",
                "error"
              );
            } else {
              mostrarMensaje("Ocurrió un error inesperado", "error");
            }
            throw new Error("Error al agregar al carrito");
          }
          return response.json();
        })
        .then((data) => {
          mostrarMensaje("Producto agregado al carrito", "exito");
        })
        .catch((error) => {
          console.error("Error en el fetch:", error);
        });
    });
  });
}

// Función para mostrar mensajes en pantalla
function mostrarMensaje(texto, tipo) {
  const mensaje = document.createElement("div");
  mensaje.className = `mensaje-${tipo}`;
  mensaje.textContent = texto;

  // Estilo básico inline
  mensaje.style.position = "fixed";
  mensaje.style.top = "309px";
  mensaje.style.right = "226px";
  mensaje.style.padding = "12px 18px";
  mensaje.style.zIndex = "9999";
  mensaje.style.borderRadius = "8px";
  mensaje.style.fontWeight = "bold";
  mensaje.style.boxShadow = "0 2px 6px rgba(0,0,0,0.3)";
  mensaje.style.backgroundColor = tipo === "exito" ? "#4CAF50" : "#f44336";
  mensaje.style.color = "white";

  document.body.appendChild(mensaje);
  setTimeout(() => {
    mensaje.remove();
  }, 3000);
}

export default agregarAlCarrito;
