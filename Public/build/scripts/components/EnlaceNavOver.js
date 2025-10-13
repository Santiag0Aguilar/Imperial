function EnlaceNavCursorOver() {
  const Enlace = document.querySelector(".dropdown");
  const BarraDesplegable = document.querySelectorAll(".dropdown-menu__item");
  Enlace.addEventListener("mouseover", () => {
    Enlace.classList.add("Enlace__Desplegable--Active");
    BarraDesplegable.forEach((e) => {
      e.classList.add("dropdown-menu__item--Active");
    });
  });
  Enlace.addEventListener("mouseout", () => {
    Enlace.classList.remove("Enlace__Desplegable--Active");
    BarraDesplegable.forEach((e) => {
      e.classList.remove("dropdown-menu__item--Active");
    });
  });
}

export default EnlaceNavCursorOver;
