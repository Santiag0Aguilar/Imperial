function BarrasActive() {
  const Barra = document.querySelector(".mobileversion");
  const Enlaces = document.querySelector(".Header__Nav");
  Barra.addEventListener("click", () => {
    Enlaces.classList.toggle("Active");
  });
}

export default BarrasActive;
