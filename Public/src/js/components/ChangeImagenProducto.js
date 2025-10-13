function ChangeImageProducto() {
  const mainImg = document.querySelector(".Producto__Imagenes__Principal img");
  const thumbs = document.querySelectorAll(
    ".Producto__Imagenes__Secundarias img"
  );
  if (!mainImg || thumbs.length === 0) return; // Verifica si los elementos existen

  thumbs.forEach((thumb) => {
    thumb.addEventListener("click", () => {
      if (mainImg.src === thumb.src) return; // evita recambio innecesario

      // Fade out
      mainImg.style.opacity = 0;

      // Espera a que la opacidad baje a 0 antes de cambiar la imagen
      setTimeout(() => {
        mainImg.src = thumb.src;
        // Fade in
        mainImg.style.opacity = 1;
      }, 200); // sincroniza con la mitad de la duración de la transición
    });
  });
}

export default ChangeImageProducto;
