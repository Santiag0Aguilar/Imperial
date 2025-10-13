function BorrarAllProductosDOM() {
  // Seleccionamos los elementos del DOM
  const BtnBorrarAllProductos = document.querySelector(".BtnDeleteCar");

  const productos = document.querySelectorAll(
    ".Carrito__Container--productos--producto"
  );
  const BtnBorrarProducto = document.querySelectorAll(".BtnDeleteProducto");
  const Precio = document.querySelector(".precioTotal");

  if (
    !BtnBorrarAllProductos ||
    !productos.length ||
    !BtnBorrarProducto.length ||
    !Precio
  ) {
    return;
  }
  // Añadimos el evento click al botón de borrar todos los productos
  BtnBorrarAllProductos.addEventListener("click", (e) => {
    // Eliminamos todos los productos del carrito
    productos.forEach((producto) => {
      producto.remove();
    });
    // Eliminamos todos los botones de borrar productos
    BtnBorrarProducto.forEach((btn) => {
      btn.remove();
    });
    // Actualizamos el precio total a 0.00
    Precio.textContent = "0.00";
  });
}

export default BorrarAllProductosDOM;
