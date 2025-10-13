function BorrarProductoDOM() {
  const Productos = document.querySelectorAll(
    ".Carrito__Container--productos--producto"
  );
  const BtnBorrarProducto = document.querySelectorAll(".BtnDeleteProducto");
  const Precio = document.querySelector(".precioTotal");

  // Validar existencia antes de continuar
  if (!Productos.length || !BtnBorrarProducto.length || !Precio) return;

  BtnBorrarProducto.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      Productos.forEach((producto) => {
        if (producto.dataset.idproducto === btn.dataset.idproducto) {
          // 🔁 Leer el precio total actual en cada clic
          const textoLimpio = Precio.textContent.replace(/[^0-9.]/g, "");
          const precioTotalActual = parseFloat(textoLimpio);

          // Leer el precio del producto a eliminar
          const precioProducto = parseFloat(
            producto
              .querySelector(".precioProducto")
              .textContent.replace(/[^0-9.]/g, "")
          );

          // Leer cantidad del producto para multiplicar por el precio
          const cantidadProducto = parseInt(
            producto.querySelector(".cantidadProducto").textContent
          );

          const totalProductoCantidades = precioProducto * cantidadProducto;

          // Calcular nuevo total
          const nuevoTotal = precioTotalActual - totalProductoCantidades;
          Precio.textContent = `${nuevoTotal.toFixed(2)}`; // Mostrar con símbolo $

          // Eliminar elementos del DOM
          producto.remove();
          btn.remove();
        }
      });
    });
  });
}

export default BorrarProductoDOM;
