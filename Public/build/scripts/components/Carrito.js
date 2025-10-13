function agregarAlCarrito(productoId, cantidad = 1) {
  fetch("agregar_carrito.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `producto_id=${productoId}&cantidad=${cantidad}`,
  })
    .then((response) => {
      if (!response.ok) throw new Error("No logueado");
      return response.json();
    })
    .then((data) => {
      localStorage.setItem("carritoCantidad", data.carritoCantidad);
      actualizarIconoCarrito();
    })
    .catch((err) => {
      alert("Debes iniciar sesión para agregar productos al carrito.");
      window.location.href = "login.php";
    });
}

function actualizarIconoCarrito() {
  const cantidad = localStorage.getItem("carritoCantidad") || 0;
  const icono = document.getElementById("carritoCantidad");
  if (icono) {
    icono.textContent = cantidad;
  }
}
